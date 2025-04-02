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
       
    }
}
