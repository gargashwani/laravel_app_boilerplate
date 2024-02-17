<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

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

        // Run migrations for that database.
        // $command = "php artisan migrate --database=$database";
        \Artisan::call('migrate', ['--database' => $database]);

        // Run seeders for that database.
        \Artisan::call('db:seed', ['--database' => $database]);
        dump("Database created");
        dump("Migrations runned");
        dump("Seeders runned");

        return 0; // Exit code 0 means everything went fine.
    }
}
