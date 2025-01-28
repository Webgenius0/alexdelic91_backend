<?php

use App\Http\Controllers\Api\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SocialAuthController;
use App\Http\Controllers\Api\Auth\RegisterController;
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


//Social Login
Route::post('/social-login', [SocialAuthController::class, 'socialLogin']);

//Register API
Route::controller(RegisterController::class)->prefix('users/register')->group(function () {
    // User Register
    Route::post('/', 'userRegister');

    // Verify OTP
    Route::post('/otp-verify', 'otpVerify');

    // Resend OTP
    Route::post('/otp-resend', 'otpResend');
});

//Login API
Route::controller(LoginController::class)->prefix('users/login')->group(function () {

    // User Login
    Route::post('/', 'userLogin');

    // Verify Email
    Route::post('/email-verify', 'emailVerify');

    // Resend OTP
    Route::post('/otp-resend', 'otpResend');

    // Verify OTP
    Route::post('/otp-verify', 'otpVerify');

    //Reset Password
    Route::post('/reset-password', 'resetPassword');
});

Route::group(['middleware' => ['jwt.verify']], function() {

    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('/data', 'userData');
        Route::post('/data/update', 'userUpdate');
        Route::post('/logout', 'logoutUser');
        Route::delete('/delete', 'deleteUser');

        Route::post('/service/provider/profile/create','create');
        Route::get('/days', 'getDays');
    });

});

// Category
Route::group(['middleware' => ['jwt.verify']], function() {

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'categories');
        Route::get('/sub-categories/{category}','subCategories');

    });

});


// Help Center

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::controller(HelpCenterController::class)->group(function() {
        Route::post('/request-for-help','requestForHelp');
    });
});

// Provider 

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::controller(ProviderController::class)->group(function() {
        Route::get('/all-providers','allProviders');
    });
});


