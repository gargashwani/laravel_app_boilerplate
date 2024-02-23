<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Tenant\App\Http\Controllers\V1\TenantController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

Route::middleware(['jwt.auth'])->prefix('v1')->name('api.')->group(function () {
    Route::group(['prefix' => 'tenant'], function () {
        // Route to create new tenant
        Route::post('create', [TenantController::class, "create_tenant"])->name('tenant.create');
    } );
});
