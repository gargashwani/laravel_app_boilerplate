<?php

namespace App\Console\Commands;

use App\Helpers\MyHelper;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        // Create new database, if there are less than 10 unused databases in the stock.
        $unused_databases = DB::connection('superadmin')->table('tenant_db_configs')->where('tenant_id', NULL)->count();
        if ($unused_databases < 10) {
            $this->create_database();
            dump("Database created successfully");
            return 0; // Exit code 0 means everything went fine.
        }
        dump("10 databases are already there in the stock!");
        return 0; // Exit code 0 means everything went fine.
    }


    protected function create_database(){
        try {
            dump("Creating a new database");

            // Create new mysql database.
            // Run migrations for that database.
            // Run seeders for that database.

            // Create new mysql database.
            $database = config('database.tenant_db_prefix').time();
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $hostname = config('database.connections.mysql.host');
            $tenant_db = [
                'database' =>$database,
                'username' =>$username,
                'password' =>$password,
                'hostname' =>$hostname
            ];

            $command = "mysql -u $username -p$password -h $hostname -e 'CREATE DATABASE IF NOT EXISTS $database'";
            exec($command);
            dump("Database created");

            MyHelper::connectTenantDB($tenant_db);

            // Get module names
            $modules = MyHelper::getModules();

            $migration = "module:migrate --database ".$database." ".$modules;
            Artisan::call($migration);
            dump("Migrations executed");

            // Run seeders for that database.
            $seeding = 'module:seed  --database '.$database.' '.$modules;
            Artisan::call($seeding);
            dump("Seeders executed.");

            // Insert newly created database details in the tenant_db_configs table as well.
            $array = [
                'id'=>Str::uuid(),
                'db_name'=>$database,
                'db_host'=>$hostname,
                'db_user'=>$username,
                'db_pass'=>$password,
            ];
            DB::connection('superadmin')->table('tenant_db_configs')->insert($array);

            return true;
        } catch (\Exception $e) {
            dd($e);
        }
    }


}
