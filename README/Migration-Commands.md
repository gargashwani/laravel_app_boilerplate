# For migration in Superadmin Database only.
php artisan module:migrate --database superadmin  Superadmin

# For migration in tenant databases.
php artisan module:migrate --database {tenant_db} {module_name}
