<?php
namespace App\Traits;

use Google\Auth\ApplicationDefaultCredentials;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

trait PushNotifications
{

    /**
     * Send a push notification to a given device token
     *
     * @param string $token Device token
     * @param string $title Notification title
     * @param string $body Notification body
     * @param array $data Additional data to be sent with the notification
     *
     * @return array|false Return the response from the FCM server or false if an error occurs
     */

    public function sendPushNotification($token, $title, $body, $data=[])
    {
        $fcmUrl = 'https://fcm.googleapis.com/v1/projects/instant-business-2da86/messages:send';

        $notitication = [
           'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data,
            'token' => $token
        ];

        try{
            $response = Http::headers([
                'Authorization' => 'Bearer' . $this->getAccessToken(),
                'Content-Type' => 'application/json',
            ])->post($fcmUrl, [
                'message' => $notitication]);
            return $response->json();
        }catch(\Exception $e){
            Log::error("Push Notification Error: $token" . $e->getMessage());
            return false;
        }
    }

    private function getAccessToken(){
        $keyPath = config('services.firebase.credentials_file');

        putenv('GOOGLE_APPLICATION_CREDENTIALS='.$keyPath);

        $SCOPES = 'https://www.googleapis.com/auth/firebase.messaging';

        $credential = ApplicationDefaultCredentials::getCredentials($SCOPES);

        $token = $credential->fetchAuthToken();

        return $token['access_token'] ?? null;
    }
}