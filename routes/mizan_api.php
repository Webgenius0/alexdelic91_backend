<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\BookMarkController;
use App\Http\Controllers\Api\User\FeedBackController;

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

});
