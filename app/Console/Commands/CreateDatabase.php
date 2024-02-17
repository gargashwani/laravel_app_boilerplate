<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;

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

            $command = "mysql -u $username -p$password -h $hostname -e 'CREATE DATABASE IF NOT EXISTS $database'";
            $this->info("Executing command: $command");
            exec($command);
            dump("Database created");

            // Configure connection with new database.
            Config::set("database.connections.$database", [
                'driver' => 'mysql',
                'host' => $hostname,
                'port' => env('DB_PORT', '3306'),
                'database' => $database,
                'username' => $username,
                'password' => $password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
            ]);
            Config::set('database.default', $database);
            // dd(DB::connection());

            // Run migrations for that database.
            // This syntax is from nwidart module.
            // Here User is the Module name. Which needs to be migrated.
            $modules = "User ";

            $migration = "module:migrate --database '.$database.' '.$modules";
            Artisan::call($migration);
            dump("Migrations executed");

            // Run seeders for that database.
            $seeding = "module:seed  --database '.$database.' '.$modules";
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
