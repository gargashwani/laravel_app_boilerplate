<?php

namespace App\Console\Commands;

use App\Helpers\MyHelper;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class TenantMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate';

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
        $all_dbs = MyHelper::getAllDatabases();

        foreach ($all_dbs as $tenant_db) {
            $database = $tenant_db->database;
            MyHelper::connectTenantDB($tenant_db);

            // Get module names
            $modules = MyHelper::getModules();

            $migration = "module:migrate --database ".$database." ".$modules;
            Artisan::call($migration);
            dump("Migrations executed");
        }
    }


}
