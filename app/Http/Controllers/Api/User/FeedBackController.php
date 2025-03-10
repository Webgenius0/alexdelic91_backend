<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
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
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        $user = auth()->user();

        if (! $user) {
            return $this->error([], "User Unauthorized", 401);
        }

        $booking = Booking::where('job_post_id', $id)->where('user_id', $user->id)->first();

        if (! $booking) {
            return $this->error([], "not found", 404);
        }

        $data = Feedback::create([
            'booking_id' => $booking->id,
            'user_id'    => $user->id,
            'feedback'   => $request->feedback,
            'rating'     => $request->rating,
        ]);

        return $this->success($data, "Feedback created successfully", 200);
    }

    public function getFeedback()
    {
        $user = auth()->user();

        if (! $user) {
            return $this->error([], "User Unauthorized", 401);
        }

        $data = Feedback::with([
            'booking'                 => function ($query) {
                $query->select('id', 'user_id', 'service_provider_id');
            },
            'booking.serviceProvider' => function ($query) {
                $query->select('id', 'name');
            },
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
