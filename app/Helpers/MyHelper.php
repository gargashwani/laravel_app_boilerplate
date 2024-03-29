<?php
// app/Helpers/MyHelper.php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class MyHelper
{

    public static function getModules(){
        $jsonContents = file_get_contents(base_path('modules_statuses.json'));
        $modules = json_decode($jsonContents, true);
        $module_names = "";
        foreach ($modules as $key=>$value) {
            if($key != "Superadmin"){
                $module_names .= $key." ";
            }
        }
        return $module_names;
    }

    public static function connectTenantDB($tenant_db){
            $database = $tenant_db['database'];
            $host = $tenant_db['host'];
            $username = $tenant_db['username'];
            $password = $tenant_db['password'];

            // Configure connection with new database.
            Config::set("database.connections.$database", [
            'driver' => 'mysql',
            'host' => $host,
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
    }

    public static function getAllDatabases(){
        // Get all the database from tenant_db_configs table.
        return DB::table('tenant_db_configs')->get();
    }
}
