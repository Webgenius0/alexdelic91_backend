<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\BookMarkController;
use App\Http\Controllers\Api\User\FeedBackController;
use App\Http\Controllers\Api\User\ServiceProviderController;

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::group(['middleware' => ['customer']], function() {

        //Bookmarks route--------
        Route::controller(BookMarkController::class)->group(function () {
            Route::post('/bookmarks/{id}', 'store');
            Route::get('/bookmarks', 'getBookmarks');
        });

        //Feedback route--------
        Route::controller(FeedBackController::class)->group(function () {
            Route::post('/feedback/{id}', 'store');
            Route::get('/feedback', 'getFeedback');
        });

    });

    Route::group(['middleware' => ['service_provider']], function() {
        
        //my reviews route--------
        Route::controller(ServiceProviderController::class)->group(function () {
            Route::get('/my-rating', 'myRating');
            Route::post('/my-rating/{id}', 'store');

            Route::post('/profile/update', 'updateProfile');
        });
    });

});
