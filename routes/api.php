<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\StyleController;
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

Route::get('/restaurant', [RestaurantController::class, "index"]);
Route::post('/restaurant/store', [RestaurantController::class, "store"]);
Route::get('/restaurant/show/{id}', [RestaurantController::class, "show"]);
Route::post('/restaurant/update/{id}', [RestaurantController::class, "update"]);
Route::delete('/restaurant/delete/{id}', [RestaurantController::class, "delete"]);

Route::get('/branch', [BranchController::class, "index"]);
Route::post('/branch/store', [BranchController::class, "store"]);
Route::get('/branch/show/{id}', [BranchController::class, "show"]);
Route::post('/branch/update/{id}', [BranchController::class, "update"]);
Route::delete('/branch/delete/{id}', [BranchController::class, "delete"]);

Route::get('/style', [StyleController::class, "index"]);
Route::post('/style/store', [StyleController::class, "store"]);
Route::get('/style/show/{id}', [StyleController::class, "show"]);
Route::post('/style/update/{id}', [StyleController::class, "update"]);
Route::delete('/style/delete/{id}', [StyleController::class, "delete"]);

Route::get('/menu', [MenuController::class, "index"]);
Route::post('/menu/store', [MenuController::class, "store"]);
Route::get('/menu/show/{id}', [MenuController::class, "show"]);
Route::post('/menu/update/{id}', [MenuController::class, "update"]);
Route::delete('/menu/delete/{id}', [MenuController::class, "delete"]);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, "me"]);
    Route::post('/logout', [AuthController::class, "logout"]);
    Route::get('/refresh', [AuthController::class, "refresh"]);

    Route::get('/meTenant', [UserTenantController::class, 'me']);
    Route::get('/refreshTenant', [UserTenantController::class, 'refresh']);
    Route::post('/logoutTenant', [UserTenantController::class, 'logout']);
});
