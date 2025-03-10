<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\NotificationController;
use App\Http\Controllers\Web\Frontend\PageController;

require __DIR__.'/auth.php';

Route::get('/page/privacy-and-policy', [PageController::class, 'privacyAndPolicy'])->name('dynamicPage.privacyAndPolicy');

Route::get('/terms-and-conditions', [PageController::class, 'termsAndConditions'])->name('dynamicPage.termsAndConditions');

Route::controller(NotificationController::class)->group(function () {
    Route::get('/send-notifications', 'sendNotifications');

});

