<?php

namespace App\Console\Commands;

use App\Helpers\MyHelper;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SuperadminMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'superadmin:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migration for Superadmin database only and using Superadmin module migrations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $migration = "module:migrate --database superadmin Superadmin";
        Artisan::call($migration);
        dump("Migrations executed for Superadmin database and using Superadmin Module migrations");
    }


}
