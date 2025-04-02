<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SocialAuthController extends Controller
{
    use ApiResponse;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function socialLogin(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'provider' => 'required|in:apple,google',
            'role'     => 'sometimes|string',
            'agree_to_terms' => 'sometimes|boolean',
        ]);

        try {
            // Fetch user data from the provider
            $socialUser = Socialite::driver($request->provider)->stateless()->userFromToken($request->token);

            if (!$socialUser || !$socialUser->getEmail()) {
                return $this->error([], "Unable to retrieve user Email", 400);
            }

            $email = $socialUser->getEmail();
            $user = User::where('email', $email)->first();
            $isNewUser = false;

            if (!$user) {
                // Create new user with additional fields
                $user = User::create([
                    'name'           => $request->input('name', $socialUser->getName() ?? 'Unknown User'),
                    'email'          => $email,
                    'password'       => bcrypt(Str::random(16)), // Random password
                    'role'           => $request->input('role', 'user'), // Default role if not provided
                    'agree_to_terms' => $request->input('agree_to_terms', 1), // Default to agreed
                    'avatar'  => $socialUser->getAvatar(),
                    'apple_id'       => $request->provider == 'apple' ? $socialUser->getId() : null,
                    'google_id'      => $request->provider == 'google' ? $socialUser->getId() : null,
                ]);
                $isNewUser = true;
            } else {
                // Update missing IDs if necessary
                if ($request->provider == 'apple' && !$user->apple_id) {
                    $user->update(['apple_id' => $socialUser->getId()]);
                }
                if ($request->provider == 'google' && !$user->google_id) {
                    $user->update(['google_id' => $socialUser->getId()]);
                }
            }

            $message = $isNewUser ? 'User registered successfully' : 'User logged in successfully';

            // Generate JWT token
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'success' => true,
                'message' => 'User authenticated successfully',
                'data'    => [
                    'user' => [
                        'id'                => $user->id,
                        'name'              => $user->name,
                        'email'             => $user->email,
                        'role'              => $user->role,
                        'address'           => $user->address ?? null,
                        'latitude'          => $user->latitude ?? null,
                        'longitude'         => $user->longitude ?? null,
                        'avatar'            => $user->profile_image ?? null,
                        'is_notices'        => $user->is_notices ?? true,
                        'is_messages'       => $user->is_messages ?? true,
                        'is_likes'          => $user->is_likes ?? true,
                        'safety_mode'       => $user->safety_mode ?? true,
                        'google_id'         => $user->google_id ?? null,
                        'apple_id'          => $user->apple_id ?? null,
                        'google_calendar_token' => $user->google_calendar_token ?? null,
                        'token'             => $token,
                    ],
                    'is_service_provider_info' => true,
                ],
                'code'    => 200,
            ]);

            // return $this->success($response, $message, 200);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "Something went wrong", 500);
        }
    }
}
