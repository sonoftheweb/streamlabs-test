<?php

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

Route::controller(\App\Http\Controllers\Auth\ExternalAuthController::class)->group(function () {
    Route::get('auth/{provider}/callback', 'handleProviderCallback');
});

Route::middleware('auth:api')->group(function () {
    Route::post('signout', function (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        return response(['message' => 'Logged out successfully'], 200);
    });


    Route::controller(\App\Http\Controllers\UserController::class)->group(function () {
        Route::prefix('users/current')->group(function () {
            Route::get('/', 'getCurrentUser');
            Route::get('revenue/donations', 'getCurrentUserDonationsRevenue');
            Route::get('revenue/merch', 'getCurrentUserMerchSales');
            Route::get('revenue/merch/top', 'getTopSaleItems');
            Route::get('followers/count', 'followersGained');
        });
    });

    Route::controller(\App\Http\Controllers\EventController::class)->group(function () {
        Route::get('events', 'index');
        Route::post('events/{id}/toggle', 'toggleEventReadStatus');
    });
});
