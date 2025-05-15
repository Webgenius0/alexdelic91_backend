<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\GoogleCalendarController;
use App\Http\Controllers\Api\HelpCenter\HelpCenterController;
use App\Http\Controllers\Api\Provider\ProviderController;
use App\Http\Controllers\Api\ReviewsController;

// Category
Route::group(['middleware' => ['jwt.verify']], function () {

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'categories');
        Route::get('/sub-categories/{category}', 'subCategories');
    });

    // Help Center

    Route::controller(HelpCenterController::class)->group(function () {
        Route::post('request-for-help', 'requestForHelp');
    });

    // Provider

    Route::controller(ProviderController::class)->group(function () {
        Route::get('/providers', 'providers');
    });

    // Reviews

    Route::controller(ReviewsController::class)->group(function () {
        Route::get('/reviews', 'reviews');
    });

    // Google Calendar

    Route::controller(GoogleCalendarController::class)->group(function () {
        Route::get('google-auth', 'redirectToGoogle');
        Route::get('google-callback', 'handleGoogleCallback');
        Route::post('sync-calendar', 'addBookingToCalendar');
    });
});


Route::controller(GoogleCalendarController::class)->group(function () {
    Route::get('google-callback', 'handleGoogleCallback');
});

Route::controller(ProviderController::class)->group(function () {
    Route::get('/provider-details/{id}', 'providerDetails');
});
