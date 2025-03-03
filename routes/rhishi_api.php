<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Banner\BannerController;
use App\Http\Controllers\Api\BookingProvider\BookingProviderController;

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

Route::get('/banners', BannerController::class);

Route::group(['middleware' => ['jwt.verify']], function () {

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
