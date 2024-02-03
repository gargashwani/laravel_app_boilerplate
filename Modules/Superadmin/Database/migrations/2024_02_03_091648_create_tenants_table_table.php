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
        Schema::create('tenants_table', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('owner_name')->nullable();
            $table->string('owner_email')->nullable();
            $table->string('domain');
            $table->string('organization_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants_table');
    }
};
