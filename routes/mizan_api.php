<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Web\FaqController;
use App\Http\Controllers\Api\Job\JobPostController;
use App\Http\Controllers\Api\User\BookMarkController;
use App\Http\Controllers\Api\User\FeedBackController;
use App\Http\Controllers\Api\Web\Job\JobAcceptController;
use App\Http\Controllers\Api\User\ServiceProviderController;
use App\Http\Controllers\Api\Provider\NotificationSettingController;

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

        //job post route--------
        Route::controller(JobPostController::class)->group(function () {
            Route::post('/job-post', 'jobPost');
            Route::get('/job-post/past', 'pastJobPost');
            Route::get('/past/history-delete', 'pastHistoryDelete');
            Route::get('/job-post/upcoming', 'upcomingJobPost');

            Route::get('/job-post/{id}', 'jobPostDetails');
            route::post('/rejob-post/{id}', 'reJobPost');
        });


    });

    Route::group(['middleware' => ['service_provider']], function() {

        //my reviews route--------
        Route::controller(ServiceProviderController::class)->group(function () {
            Route::get('/my-rating', 'myRating');
            Route::post('/my-rating/{id}', 'store');

            Route::post('/profile/update', 'updateProfile');
            Route::post('/my-availability', 'myAvailability');
        });

        //job post route--------
        Route::controller(JobPostController::class)->group(function () {
            Route::get('/job-post-list', 'getJobPost');
            Route::get('/singel-job-post/{id}', 'singelJobPost');
        });

        Route::controller(JobAcceptController::class)->group(function () {
            Route::post('/job-accept/{id}', 'jobAccept');
        });

        Route::controller(NotificationSettingController::class)->group(function () {
            Route::post('/notifications-setting', 'notificationsSetting');
        });
    });

});

Route::controller(FaqController::class)->group(function () {
    Route::get('/faq', 'getFaq');
});
