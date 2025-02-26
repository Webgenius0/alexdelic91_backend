<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeedBackController extends Controller
{
    use ApiResponse;

    public function store(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'feedback' => 'nullable|string',
            'rating'   => 'required|numeric|min:1|max:5',
        ]);


        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }


        $user = auth()->user();


        if (!$user) {
            return $this->error([], "User Unauthorized", 401);
        }


        $existingFeedback = Feedback::where('user_id', $user->id)
            ->where('booking_id', $id)
            ->first();

        if ($existingFeedback) {
            return $this->error($existingFeedback, "Feedback Already Given", 200);
        } else {

            $data = Feedback::create([
                'user_id'    => $user->id,
                'booking_id' => $id,
                'rating'     => $request->rating,
                'feedback'   => $request->feedback,
            ]);

            if (!$data) {
                return $this->error([], "Something went wrong", 500);
            }

            return $this->success($data, "Feedback Added Successfully", 200);
        }
    }


    public function getFeedback()
    {
        $user = auth()->user();

        if (! $user) {
            return $this->error([], "User Unauthorized", 401);
        }

        $data = Feedback::with([
            'booking' => function ($query) {
                $query->select('id', 'user_id', 'service_provider_id');
            },
            'booking.serviceProvider' => function ($query) {
                $query->select('id', 'name');
            }
        ])
            ->where('user_id', $user->id)
            ->get()
            ->map(function ($feedback) {
                $feedback->formatted_created_at = $feedback->created_at->format('M d, Y');
                return $feedback;
            });

        if ($data->isEmpty()) {
            return $this->error([], "Feedback not found", 200);
        }

        return $this->success($data, "Feedback fetched successfully", 200);
    }
}
