<?php
use App\Models\DynamicPage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\NotificationController;

require __DIR__.'/auth.php';

Route::get('/privacy-policy', function () {
    $privacyPolicy = DynamicPage::where('id', 1)->first();
    return response()->json($privacyPolicy);
});

Route::get('/terms-and-conditions', function () {
    $termsAndConditions = DynamicPage::where('id', 2)->first();
    return response()->json($termsAndConditions);
});

Route::controller(NotificationController::class)->group(function () {
    Route::get('/send-notifications', 'sendNotifications');
   
});

