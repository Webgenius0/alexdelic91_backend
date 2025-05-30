<?php
namespace App\Http\Controllers\Api;

use App\Models\Day;
use App\Models\User;
use App\Models\Category;
use App\Models\Subcategory;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\OtherCategory;
use App\Models\ServiceLocation;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Models\ServiceProviderImage;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceProviderProfile;
use App\Models\ServiseProviderWorkDay;
use Illuminate\Support\Facades\Validator;
use App\Models\ServiceProviderSubcategory;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Fetch Login User Data
     *
     * @return \Illuminate\Http\JsonResponse  JSON response with success or error.
     */

    public function userData()
    {

        $user = auth()->user();

        $data = User::with('serviceProviderProfile.serviceProviderImage', 'serviceProviderProfile.workingDays', 'serviceProviderProfile.subCategories', 'serviceProviderProfile.otherCategories')->withAvg('feedbacks', 'rating')->where('id', $user->id)->first();

        if (! $data) {
            return $this->error([], 'User Not Found', 200);
        }

        if ($data->feedbacks_avg_rating !== null) {
            $data->feedbacks_avg_rating = round($data->feedbacks_avg_rating, 1);
        }else{
            $data->feedbacks_avg_rating = 0;
        }

        return $this->success($data, 'User data fetched successfully', 200);
    }

    /**
     * Update User Information
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request with the register query.
     * @return \Illuminate\Http\JsonResponse  JSON response with success or error.
     */

    public function userUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'avatar'  => 'nullable|image|mimes:jpeg,png,jpg,svg|max:20480',
            'email'   => 'nullable|email|unique:users,email,' . auth()->user()->id,
            'address' => 'nullable|string',
            'name'    => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        try {
            // Find the user by ID
            $user = auth()->user();

            // If user is not found, return an error response
            if (! $user) {
                return $this->error([], "User Not Found", 200);
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
            $user->email   = $request->email;
            $user->address = $request->address;
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
    public function logoutUser()
    {

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
    public function deleteUser()
    {
        try {
            // Get the authenticated user
            $user = auth()->user();

            // If user is not found, return an error response
            if (! $user) {
                return $this->error([], "User Not Found", 200);
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
            'business_name'       => 'nullable|string|max:255',
            'category_id'         => 'nullable|string|max:255',
            'address'             => 'nullable|string|max:255',
            'latitude'            => 'nullable',
            'longitude'           => 'nullable',
            'phone'               => 'nullable|numeric',
            'service_location_id' => 'nullable',
            'description'         => 'nullable',
            'city'                => 'nullable|string|max:255',
            'division'            => 'nullable|string|max:255',
            'zip_code'            => 'nullable|string|max:255',
            'start_time'          => 'nullable|string',
            'end_time'            => 'nullable|string',
            'images.*'            => 'nullable|image|mimes:png,jpg,jpeg',
            'subcategories'       => 'nullable|array',
            'subcategories.*'     => 'nullable|integer',
            'days'                => 'nullable|array',
            'days.*'              => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        $user = Auth::user(); // Get the authenticated user
        if (! $user) {
            return $this->error([], "User Not Found", 200);
        }

        try {
            DB::beginTransaction();

            // Create service provider profile
            $service_provider = ServiceProviderProfile::updateOrCreate(
                [
                    'user_id' => $user->id,
                ], [
                    'user_id'             => $user->id,
                    'business_name'       => $request->business_name,
                    'category_id'         => $request->category_id,
                    'address'             => $request->address,
                    'latitude'            => $request->latitude,
                    'longitude'           => $request->longitude,
                    'phone'               => $request->phone,
                    'service_location_id' => $request->service_location_id,
                    'description'         => $request->description,
                    'city'                => $request->city,
                    'division'            => $request->division,
                    'zip_code'            => $request->zip_code,
                    'start_time'          => $request->start_time,
                    'end_time'            => $request->end_time,
                ]);

            $category = Category::find($request->category_id);

            if ($category && in_array(strtolower($category->category_name), ['others', 'other'])) {
               $other_category = OtherCategory::create([
                    'service_provider_profile_id' => $service_provider->id,
                    'category_id'         => $category->id,
                    'category_name'       => $request->category_name,
                    'status'              => 'inactive',
                ]);
            }
            // Handle gallery images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = uploadImage($image, 'service/images');
                    ServiceProviderImage::create([
                        'service_provider_id' => $service_provider->id,
                        'images'              => $imageName,
                    ]);
                }
            }
            
            if($request->other) {
                $other = Subcategory::create([
                    'category_id'      => $request->category_id,
                    'subcategory_name' => $request->other,
                ]);

                ServiceProviderSubcategory::create([
                    'service_provider_id' => $service_provider->id,
                    'subcategory_id'      => $other->id,
                ]);
            }

            // Handle subcategories
            if (is_array($request->subcategories)) {
                foreach ($request->subcategories as $subcategory_id) {
                    ServiceProviderSubcategory::create([
                        'service_provider_id' => $service_provider->id,
                        'subcategory_id'      => $subcategory_id,
                    ]);
                }
            }

            // Handle workdays
            if (is_array($request->days)) {
                foreach ($request->days as $day_id) {
                    ServiseProviderWorkDay::create([
                        'service_provider_id' => $service_provider->id,
                        'day_id'              => $day_id,
                    ]);
                }
            }
            $service_provider->load(['otherCategories','subCategories', 'workingDays', 'serviceProviderImage']);

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
        if (! $days) {
            return $this->error([], 'Days not found', 200);
        }
        return $this->success($days, 'Days fetched successfully', 200);
    }

    public function getLocation()
    {
        $data = ServiceLocation::all();
        if (! $data) {
            return $this->error([], 'Locations not found', 200);
        }
        return $this->success($data, 'Locations fetched successfully', 200);
    }

    public function updateLatAndLng(Request $request)
    {
        $user = auth()->user();
        if (! $user) {
            return $this->error([], 'User not found', 200);
        }
        $user->latitude  = $request->latitude;
        $user->longitude = $request->longitude;
        $user->save();
        return $this->success($user, 'Location updated successfully', 200);
    }
}
