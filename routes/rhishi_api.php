<?php

use App\Http\Controllers\Api\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SocialAuthController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\BookingProvider\BookingProviderController;
use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\HelpCenter\HelpCenterController;
use App\Http\Controllers\Api\Provider\ProviderController;
use App\Http\Controllers\Api\UserController;

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

Route::group(['middleware' => ['jwt.verify']], function() {

    Route::controller(BookingProviderController::class)->prefix('booking')->group(function () {
        Route::post('/create', 'book');
        Route::get('/get/past', 'pastBookings');
        Route::get('/get/upcoming', 'upcomingBookings');
        Route::put('/edit/{id}', 'edit');

        Route::get('/get/single/{id}', 'single');
        Route::get('/get/provider/single/{id}', 'providerSingle');
        Route::get('/get/provider/rating/single/{id}', 'providerWithRatingSingle');

        Route::post('/cancel/{id}', 'cancel');
        Route::post('/booked/{id}', 'booked');

        Route::get('/get/provider', 'getProviderBookings');
        Route::get('/get/provider/history', 'getProviderBookingsHistory');
    });

});



