<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('register', [UserController::class,'register']);
Route::post('phoneVerification', [UserController::class,'phoneVerification']);
Route::post('login', [UserController::class,'login']);
Route::post('sendForgetCode', [UserController::class,'sendForgetCode']);
Route::post('ForgetCode', [UserController::class,'ForgetCode']);

Route::middleware('auth:api')->group( function () {
    Route::get('auth', [UserController::class,'getAuthUser']);
    Route::get('viewCategory', [CategoryController::class,'index']);
    Route::post('storeCategory', [CategoryController::class,'store']);
    Route::get('viewProduct', [ProductController::class,'index']);
    Route::post('storeProduct', [ProductController::class,'store']);
    Route::post('showProduct', [ProductController::class,'show']);
    Route::post('favouriteProduct', [ProductController::class,'favourite']);
    Route::post('rateProduct', [ProductController::class,'rate']);
    Route::get('viewAddress', [AddressController::class,'view']);
    Route::post('addAddress', [AddressController::class,'save']);


    Route::get('index',  [CartController::class,'index']);
    Route::post('add',    [CartController::class,'add']);
   // Route::post('update', [CartController::class,'update']);
    Route::post('delete', [CartController::class,'delete']);


    Route::get('viewOrder',  [OrderController::class,'index']);
    Route::post('addOrder',  [OrderController::class,'add']);


    Route::post('/payment', [PaymentController::class,'payWithpaypal'])->name('payment');

});
Route::get('/payment/status', [PaymentController::class,'getPaymentStatus'])->name('status');


/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/
