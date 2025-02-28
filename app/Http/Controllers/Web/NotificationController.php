<?php

namespace App\Http\Controllers\Web;


use App\Models\User;
use App\Traits\PushNotifications;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    use PushNotifications;

    public function sendNotifications()
    {
        $user = auth()->user();
        $deviceTokens =$user->fcm_token;
        dd($deviceTokens);

        $title = 'Test Notification';
        $body = 'This is a test notification';

        $data = [
            'key1' => 'value1',
            'key2' => 'value2'
        ];

        $response = $this->sendPushNotification($deviceTokens, $title, $body, $data);

        return response()->json(['success'=>true ,'response'=>$response]);
    }
}
