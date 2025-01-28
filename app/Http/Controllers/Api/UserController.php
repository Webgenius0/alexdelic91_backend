<?php

namespace App\Http\Controllers\Api;

use App\Models\Day;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Models\ServiceProviderImage;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceProviderProfile;
use App\Models\ServiseProviderWorkDay;
use Illuminate\Support\Facades\Validator;
use App\Models\ServiceProviderSubcategory;

class UserController extends Controller {
    use ApiResponse;

    /**
     * Fetch Login User Data
     *
     * @return \Illuminate\Http\JsonResponse  JSON response with success or error.
     */

    public function userData() {

        $user = auth()->user();

        if (!$user) {
            return $this->error([], 'User Not Found', 404);
        }

        return $this->success($user, 'User data fetched successfully', 200);
    }

    /**
     * Update User Information
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request with the register query.
     * @return \Illuminate\Http\JsonResponse  JSON response with success or error.
     */

    public function userUpdate(Request $request) {

        $validator = Validator::make($request->all(), [
            'avatar'  => 'nullable|image|mimes:jpeg,png,jpg,svg|max:5120',
            'name'    => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        try {
            // Find the user by ID
            $user = auth()->user();

            // If user is not found, return an error response
            if (!$user) {
                return $this->error([], "User Not Found", 404);
            }

            if ($request->hasFile('avatar')) {

                if ($user->avatar) {
                    $previousImagePath = public_path($user->avatar);
                    if (file_exists($previousImagePath)) {
                        unlink($previousImagePath);
                    }
                }

                $image     = $request->file('avatar');
                $imageName = uploadImage($image, 'User/Avatar');
            } else {
                $imageName = $user->avatar;
            }

            $user->name    = $request->name;
            $user->avatar  = $imageName;

            $user->save();

            return $this->success($user, 'User updated successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Logout the authenticated user's account
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse JSON response with success or error.
     */
    public function logoutUser() {

        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return $this->success([], 'Successfully logged out', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }

    }

    /**
     * Delete the authenticated user's account
     *
     * @return \Illuminate\Http\JsonResponse JSON response with success or error.
     */
    public function deleteUser() {
        try {
            // Get the authenticated user
            $user = auth()->user();

            // If user is not found, return an error response
            if (!$user) {
                return $this->error([], "User Not Found", 404);
            }

            // Delete the user's avatar if it exists
            if ($user->avatar) {
                $previousImagePath = public_path($user->avatar);
                if (file_exists($previousImagePath)) {
                    unlink($previousImagePath);
                }
            }

            // Delete the user
            $user->delete();

            return $this->success([], 'User deleted successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {
        // dd($request->all());
        // Validation rules
        $validator = Validator::make($request->all(), [
            'business_name' => 'required|string|max:255',
            'category_id' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'service_location_id' => 'required',
            'description' => 'required',
            'city' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'zip_code' => 'required|string|max:255',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'images.*' => 'nullable|image|mimes:png,jpg,jpeg|max:4048',
            'subcategories' => 'required|array',
            'subcategories.*' => 'required|integer',
            'days' => 'required|array',
            'days.*' => 'required|integer',
        ]);
    
        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }
    
        $user = Auth::user(); // Get the authenticated user
        if (!$user) {
            return $this->error([], "User Not Found", 404);
        }
    
        try {
            DB::beginTransaction();
    
            // Create service provider profile
            $service_provider = ServiceProviderProfile::create([
                'user_id' => $user->id,
                'business_name' => $request->business_name,
                'category_id' => $request->category_id,
                'address' => $request->address,
                'phone' => $request->phone,
                'service_location_id' => $request->service_location_id,
                'description' => $request->description,
                'city' => $request->city,
                'division' => $request->division,
                'zip_code' => $request->zip_code,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
            ]);
    
            // Handle gallery images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = uploadImage($image, 'service/images');
                    ServiceProviderImage::create([
                        'service_provider_id' => $service_provider->id,
                        'images' => $imageName,
                    ]);
                }
            }
    
            // Handle subcategories
            if (is_array($request->subcategories)) {
                foreach ($request->subcategories as $subcategory_id) {
                    ServiceProviderSubcategory::create([
                        'service_provider_id' => $service_provider->id,
                        'subcategory_id' => $subcategory_id,
                    ]);
                }
            }
    
            // Handle workdays
            if (is_array($request->days)) {
                foreach ($request->days as $day_id) {
                    ServiseProviderWorkDay::create([
                        'service_provider_id' => $service_provider->id,
                        'day_id' => $day_id,
                    ]);
                }
            }
    
            DB::commit();
            return $this->success($service_provider, 'Service Provider created successfully', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage(), 500);
        }
    }

    public function getDays()
    {
        $days = Day::all();
        if (!$days) {
            return $this->error([], 'Days not found', 404);
        }
        return $this->success($days, 'Days fetched successfully', 200);
    }

}
