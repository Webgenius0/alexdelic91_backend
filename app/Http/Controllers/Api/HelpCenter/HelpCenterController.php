<?php

namespace App\Http\Controllers\Api\HelpCenter;

use App\Http\Controllers\Controller;
use App\Mail\HelpCenterMail;
use App\Models\HelpCenter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Mail;

class HelpCenterController extends Controller
{
    use ApiResponse;

    public function requestForHelp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        $user = auth()->user();

        if (!$user) {
            return $this->error([], "User Unauthorized", 401);
        }

        $data = HelpCenter::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ]);

        $adminEmail = User::where('role', 'admin')->first()->email;

        if($adminEmail == "admin@admin.com") {
            $adminEmail = config('app.testing_to_mail');
        }

        Mail::to($adminEmail)->send(new HelpCenterMail($data));

        return $this->success($data, 'Help request sent successfully', 201);
    }
}
