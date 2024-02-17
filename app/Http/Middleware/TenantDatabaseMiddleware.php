<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;

class TenantDatabaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Determine the current tenant (you can customize this logic).
        $tenant = $request->header('X-Tenant-ID'); // Example: Get tenant ID from header.

        if ($tenant) {
            // Configure the database connection dynamically.
            Config::set('database.default', $tenant);

            // Get the tenant's database details.
            $database = $tenant;
            $host = env('DB_HOST', '127.0.0.1');
            $username = env('DB_USERNAME', 'root');
            $password = env('DB_PASSWORD', '');


            // You can also set other configurations dynamically if needed.
            // Config::set("database.connections.$tenant", [...]);
            // Dynamically configure the database connection.
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
            // Now the default database connection is set to the tenant's database.
            Config::set('database.default', $database);

            return $next($request);
        }

        abort(400, 'Tenant not specified.');
    }
}
