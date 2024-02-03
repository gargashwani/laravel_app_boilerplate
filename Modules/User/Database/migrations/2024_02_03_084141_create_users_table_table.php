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
        Schema::create('users_table', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('role_id')->nullable();
            $table->string('email')->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->string('phone')->nullable();
            $table->dateTime('phone_verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_table');
    }
};