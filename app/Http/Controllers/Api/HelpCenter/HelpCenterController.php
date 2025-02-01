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

    public function requestForHelp(Request $request) {
        $validatedData = Validator::make($request->all(), [
            'user_id' => 'required',
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        if($validatedData->fails()) {
           return $this->error($validatedData->errors()->first(), 'Validation Error', 422);
        }

       $data = HelpCenter::create($validatedData->validated());
       $adminEmail = User::where('role', 'admin')->first()->email;

       Mail::to($adminEmail)->send(new HelpCenterMail($data));

       return $this->success($data, 'Help request sent successfully', 201);
    }
}
