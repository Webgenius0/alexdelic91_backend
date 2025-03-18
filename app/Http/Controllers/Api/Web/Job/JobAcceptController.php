<?php

namespace App\Http\Controllers\Api\Web\Job;

use App\Models\Booking;
use App\Models\JobPost;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Enum\NotificationType;
use App\Http\Controllers\Controller;
use App\Notifications\NewNotification;
use Illuminate\Support\Facades\Validator;

class JobAcceptController extends Controller
{
    use ApiResponse;
    public function jobAccept(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'booking_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        $user = auth()->user();

        if (!$user) {
            return $this->error([], 'User not found', 200);
        }

        $job = JobPost::find($id);

        if (!$job) {
            return $this->error([], 'Job not found', 200);
        }

        $data = Booking::create([
            'user_id' => $job->user_id,
            'service_provider_id' => $user->id,
            'job_post_id' => $job->id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'booking_date' => $request->booking_date,
            'address' => $job->location,
            'latitude' => $job->latitude,
            'longitude' => $job->longitude,
            'notes' => $request->notes,
        ]);

        $job->update([
            'status' => 'booked',
        ]);

        $data->user->notify(new NewNotification(
            message: 'Your job has been accepted',
            channels: ['database'],
            type: NotificationType::SUCCESS,
        ));

        return $this->success($data, 'Job accepted successfully', 200);
    }
}
