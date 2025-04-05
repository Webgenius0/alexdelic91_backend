<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Feedback;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\ProviderReview;
use App\Http\Controllers\Controller;
use App\Models\ServiceProviderImage;
use App\Models\ServiseProviderWorkDay;
use Google\Service\MigrationCenterAPI\PayloadFile;
use Illuminate\Support\Facades\Validator;

class ServiceProviderController extends Controller
{
    use ApiResponse;

    public function myRating()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->error([], 'User Unauthorized', 200);
        }

        $data = Feedback::with('user')->whereHas('booking', function ($query) use ($user) {
            $query->where('service_provider_id', $user->id);
        })->get();

        if ($data->isEmpty()) {
            return $this->error([], 'No Feedback Found', 200);
        }

        return $this->success($data, 'Feedback fetched successfully', 200);
    }

    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'nullable|string',
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return $this->error([], $validator->errors(), 400);
        }

        $user = auth()->user();

        if (! $user) {
            return $this->error([], "User Unauthorized", 401);
        }

        $existingFeedback = Feedback::where('user_id', $user->id)
            ->where('booking_id', $id)
            ->first();

        if ($existingFeedback) {
            return $this->error([], "Feedback Already Exists", 400);
        } else {
            $data = ProviderReview::create([
                'user_id'             => $user->id,
                'booking_id'          => $id,
                'message'            => $request->input('message'),
                'rating'              => $request->input('rating'),
            ]);

            if ($data) {
                return $this->success($data, 'Feedback created successfully', 200);
            }
        }
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'nullable|max:100|min:2',
            'phone' => 'nullable|numeric',
            'address' => 'nullable|string',
            'instagram' => 'nullable|string|url',
            'facebook' => 'nullable|string|url',
            'tiktok' => 'nullable|string|url',
            'website' => 'nullable|string|url',
            'description' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'images.*' => 'nullable|image|mimes:png,jpg,jpeg|max:4048',
        ]);

        if ($validator->fails()) {
            return $this->error([], $validator->errors(), 400);
        }

        $user = auth()->user();

        if (!$user) {
            return $this->error([], "User Unauthorized", 401);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                $previousImagePath = public_path($user->avatar);
                if (file_exists($previousImagePath)) {
                    unlink($previousImagePath);
                }
            }
            $image                        = $request->file('avatar');
            $imageName                    = uploadImage($image, 'user');
            $user->avatar = $imageName;
        } else {
            $user->avatar = $user->avatar;
        }

        try {
            $user->update([
                'name' => $request->name,
                'avatar' => $user->avatar,
            ]);

            $profile = $user->serviceProviderProfile()->updateOrCreate([], [
                'business_name' => $user->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'instagram' => $request->instagram,
                'facebook' => $request->facebook,
                'tiktok' => $request->tiktok,
                'website' => $request->website,
                'description' => $request->description
            ]);

            // Handle gallery images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = uploadImage($image, 'service/images');
                    $images = ServiceProviderImage::create([
                        'service_provider_id' => $profile->id,
                        'images' => $imageName,
                    ]);
                }
            }
            $profile->load(['serviceProviderImage']);

            if ($profile) {
                return $this->success($profile, 'Profile updated successfully', 200);
            }
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    public function myAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'nullable|array',
            'days.*' => 'nullable|integer',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        if ($validator->fails()) {
            return $this->error([], $validator->errors(), 400);
        }
        $user = auth()->user();

        if (!$user) {
            return $this->error([], "User Unauthorized", 401);
        }

        $data = $user->serviceProviderProfile()->first();

        // dd($data);

        if (is_array($request->day_id)) {
            // dd($request->day_id);
            ServiseProviderWorkDay::where('service_provider_id', $data->id)->delete();
            foreach ($request->day_id as $day_id) {
                ServiseProviderWorkDay::create([
                    'service_provider_id' => $data->id,
                    'day_id' => $day_id,
                ]);
            }
        }

        $data->start_time = $request->start_time;
        $data->end_time = $request->end_time;
        $data->save();

        $data->load(['workingDays']);

        if ($data) {
            return $this->success($data, 'Availability fetched successfully', 200);
        }
    }
}
