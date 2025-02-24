<?php

use App\Http\Controllers\Web\NotificationController;
use App\Models\DynamicPage;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::get('/privacy-policy', function () {
    $privacyPolicy = DynamicPage::where('id', 1)->first();
    return response()->json($privacyPolicy);
});

Route::get('/terms-and-conditions', function () {
    $termsAndConditions = DynamicPage::where('id', 2)->first();
    return response()->json($termsAndConditions);
});

Route::get('/test', [NotificationController::class, 'test_firebase']);

Route::post('/store_fcm', [NotificationController::class, 'store_fcm']);
