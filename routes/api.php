<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\StyleController;
use App\Http\Controllers\SubOptionController;
use App\Http\Controllers\TenantProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserTenantController;
use App\Http\Controllers\Web\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatisticsVisitController;
use App\Http\Controllers\WebSocketController;

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
use Ably\AblyRest;

Route::get('/send_message', function () {
    // Initialize Ably
    $client = new AblyRest('6jY4lw.wSFrTw:_FCLjMx0u5670bHjTKtGwKEj42beEriZu1190F-OY38');
    
    // Publish a message
    $channel = $client->channel('chat');
    $channel->publish('MessageSent', 'Hello!');
    
    return 'Message sent!';
});

Route::post('/sendMessage', [WebSocketController::class, "sendMessage"]);

Route::post('/register', [AuthController::class, "register"]);
Route::post('/login', [AuthController::class, "login"]);

Route::get('/tenantProfile', [TenantProfileController::class, "index"]);
Route::post('/tenantProfile/store', [TenantProfileController::class, "store"]);

Route::post('/registerTenant', [UserTenantController::class, "register"]);
Route::post('/loginTenant', [UserTenantController::class, "login"]);

// Define your routes here
Route::post('/getBranch', [WebController::class, "getBranch"]);
Route::post('/getMenu', [WebController::class, "getMenu"]);
Route::post('/getCategory', [WebController::class, "getCategory"]);
Route::post('/getProduct', [WebController::class, "getProduct"]);
Route::post('/getOption', [WebController::class, "getOption"]);
Route::post('/getSocialMedia', [WebController::class, "getSocialMedia"]);
Route::post('/getStyle', [WebController::class, "getStyle"]);
Route::post('/getProductById/{id}&{visit}', [WebController::class, "getProductById"]);
Route::get('/getProductCommon/{branch_id}', [WebController::class, "getProductCommon"]);
Route::get('/getProductNew/{branch_id}', [WebController::class, "getProductNew"]);
Route::get('/getProductPriceOffer/{branch_id}', [WebController::class, "getProductPriceOffer"]);

Route::post('/showVisitWithDate', [StatisticsVisitController::class, "showVisitWithDate"]);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/restaurant', [RestaurantController::class, "index"]);
    Route::get('/branch', [BranchController::class, "index"]);
    Route::get('/style', [StyleController::class, "index"]);
    Route::get('/socialMedia', [SocialMediaController::class, "index"]);
    Route::get('/subOption', [SubOptionController::class, "index"]);
    Route::get('/menu', [MenuController::class, "index"]);
    Route::get('/category', [CategoryController::class, "index"]);
    Route::get('/product', [ProductController::class, "index"]);
    Route::get('/option', [OptionController::class, "index"]);

    Route::get('/me', [AuthController::class, "me"]);
    Route::post('/logout', [AuthController::class, "logout"]);
    Route::get('/refresh', [AuthController::class, "refresh"]);
    Route::get('/refreshWithIdRes/{restaurant_id}', [AuthController::class, 'refreshWithIdRes']);

    Route::get('/user', [UserController::class, "index"]);
    Route::post('/user/update/{id}', [UserController::class, "update"]);
    Route::delete('/user/destroy/{id}', [UserController::class, "destroy"]);

    Route::get('/userTenant', [UserTenantController::class, "index"]);
    Route::post('/userTenant/update/{id}', [UserTenantController::class, "update"]);
    Route::delete('/userTenant/destroy/{id}', [UserTenantController::class, "destroy"]);

    Route::post('/restaurant/store', [RestaurantController::class, "store"]);
    Route::get('/restaurant/show/{id}', [RestaurantController::class, "show"]);
    Route::post('/restaurant/update/{id}', [RestaurantController::class, "update"]);
    Route::delete('/restaurant/delete/{id}', [RestaurantController::class, "delete"]);


    Route::post('/style/store', [StyleController::class, "store"]);
    Route::get('/style/show/{id}', [StyleController::class, "show"]);
    Route::post('/style/update/{id}', [StyleController::class, "update"]);
    Route::delete('/style/delete/{id}', [StyleController::class, "delete"]);

    Route::post('/branch/store', [BranchController::class, "store"]);
    Route::get('/branch/show/{id}', [BranchController::class, "show"]);
    Route::post('/branch/update/{id}', [BranchController::class, "update"]);
    Route::delete('/branch/delete/{id}', [BranchController::class, "delete"]);
});
Route::middleware('check.restaurant')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {


        Route::get('/restaurant/getRestaurant', [RestaurantController::class, "getRestaurant"]);



        Route::get('/branch/getBranch', [BranchController::class, "getBranch"]);



        Route::get('/style/getStyle', [StyleController::class, "getStyle"]);




        Route::get('/socialMedia/getSocialMedia', [SocialMediaController::class, "getSocialMedia"]);
        Route::post('/socialMedia/store', [SocialMediaController::class, "store"]);
        Route::get('/socialMedia/show/{id}', [SocialMediaController::class, "show"]);
        Route::post('/socialMedia/update/{id}', [SocialMediaController::class, "update"]);
        Route::delete('/socialMedia/delete/{id}', [SocialMediaController::class, "delete"]);





        Route::post('/subOption/store', [SubOptionController::class, "store"]);
        Route::get('/subOption/show/{id}', [SubOptionController::class, "show"]);
        Route::post('/subOption/update/{id}', [SubOptionController::class, "update"]);
        Route::delete('/subOption/delete/{id}', [SubOptionController::class, "delete"]);


        // Define your routes here






        Route::get('/menu/getMenu/{branch_id}', [MenuController::class, "getMenu"]);
        Route::post('/menu/store', [MenuController::class, "store"]);
        Route::get('/menu/show/{id}', [MenuController::class, "show"]);
        Route::post('/menu/update/{id}', [MenuController::class, "update"]);
        Route::delete('/menu/delete/{id}', [MenuController::class, "delete"]);


        Route::get('/category/getCategory/{branch_id}', [CategoryController::class, "getCategory"]);
        Route::post('/category/store', [CategoryController::class, "store"]);
        Route::get('/category/show/{id}', [CategoryController::class, "show"]);
        Route::post('/category/update/{id}', [CategoryController::class, "update"]);
        Route::delete('/category/delete/{id}', [CategoryController::class, "delete"]);



        Route::get('/product/getProduct/{branch_id}', [ProductController::class, "getProduct"]);
        Route::get('/product/getProductWithOption/{category_id}', [ProductController::class, "getProductWithOption"]);
        Route::get('/product/getProductWithOptionById/{product_id}', [ProductController::class, "getProductWithOptionById"]);
        Route::post('/product/store', [ProductController::class, "store"]);
        Route::get('/product/show/{id}', [ProductController::class, "show"]);
        Route::post('/product/update/{id}', [ProductController::class, "update"]);
        Route::delete('/product/delete/{id}', [ProductController::class, "delete"]);
        Route::post('/product/sortPrdouct', [ProductController::class, "sortPrdouct"]);
        Route::get('/product/getsortPrdouct/{category_id}', [ProductController::class, "getsortPrdouct"]);


        Route::get('/option/getOption/{product_id}', [OptionController::class, "getOption"]);
        Route::post('/option/store', [OptionController::class, "store"]);
        Route::get('/option/show/{id}', [OptionController::class, "show"]);
        Route::post('/option/update/{id}', [OptionController::class, "update"]);
        Route::delete('/option/delete/{id}', [OptionController::class, "delete"]);

        Route::get('/meTenant', [UserTenantController::class, 'me']);
        Route::get('/refreshTenant', [UserTenantController::class, 'refresh']);
        Route::post('/logoutTenant', [UserTenantController::class, 'logout']);
    });
});
