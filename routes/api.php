<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TenantProfileController;
use App\Http\Controllers\UserTenantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, "register"]);
Route::post('/login', [AuthController::class, "login"]);

Route::get('/tenantProfile', [TenantProfileController::class, "index"]);
Route::post('/tenantProfile/store', [TenantProfileController::class, "store"]);

Route::post('/registerTenant', [UserTenantController::class, "register"]);
Route::post('/loginTenant', [UserTenantController::class, "login"]);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, "me"]);
    Route::post('/logout', [AuthController::class, "logout"]);
    Route::get('/refresh', [AuthController::class, "refresh"]);

    Route::get('/meTenant', [UserTenantController::class, 'me']);
    Route::get('/refreshTenant', [UserTenantController::class, 'refresh']);
    Route::post('/logoutTenant', [UserTenantController::class, 'logout']);
});
