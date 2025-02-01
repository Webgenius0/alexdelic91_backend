<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Kreait\Firebase\Contract\Messaging;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class NotificationController extends Controller
{
    protected $auth, $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->auth = Firebase::auth();
        // $this->auth = Firebase::project('ComplaintManagementSystem')->auth();
        $this->messaging = $messaging;

    }
    public function test_firebase(){
        $notification = [
            "title" => "User test msg",
            "body" => "Mr ABC, oy",  
            // "imageUrl"=>"http://cms.outreachmarketing.com.pk/storage/logos/app/logo-small.png"
        ];
        $data = [
            "id" => 105
            
        ];
        // $fb = new FirebaseAuthController();

        // $fcm = new FirebaseAuthController($this->messaging);
        $fcm_tokens = [
            auth()->user()->fcm_token,
        ];

        $resp = $this->send($fcm_tokens , $notification, $data);
        
        dd('sent', $resp, $fcm_tokens);
    }

    public function store_fcm(Request $request){
        $fcm_validator = Validator::make($request->all(), [
            'fcm_token' => ['required']
        ]);

        if ($fcm_validator->fails()) {
            return response()->json(['error' => $fcm_validator->errors()], 402);
        }

        $data = $fcm_validator->validated();

        User::where('id', auth()->user()->id)->update([
            'fcm_token' => $data['fcm_token']
        ]);
        return response()->json('done', 200);
    }

   
    public function send($fcm_tokens, $notification, $data){

        // $topic = 'a-topic';

        // // $message = CloudMessage::withTarget('topic', $topic)
        // //     ->withNotification($notification) // optional
        // //     ->withData($data) // optional
        // // ;

        // $message = CloudMessage::fromArray([
        //     'topic' => $topic,
        //     'notification' => ['hello'], // optional
        //     'data' => [], // optional
        // ]);

        // $this->messaging->send($message);


        $deviceTokens = $fcm_tokens ;

        $notification = Notification::fromArray($notification);
       
        $message = CloudMessage::fromArray([
            'notification' => $notification,
            'data' => $data,
              
        ]); 

    
        $sendReport = $this->messaging->sendMulticast($message, $deviceTokens);
    }
}
