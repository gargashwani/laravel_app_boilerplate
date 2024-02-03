<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenant_db_configs', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id')->nullable();
            $table->string('db_url')->nullable();
            $table->string('db_name')->nullable();
            $table->string('db_host')->nullable();
            $table->string('db_user')->nullable();
            $table->string('db_pass')->nullable();
            $table->string('server_id')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_db_configs');
    }
};
