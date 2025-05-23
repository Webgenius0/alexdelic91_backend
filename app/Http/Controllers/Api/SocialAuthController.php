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
use Firebase\JWT\JWT;

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
            'role'     => 'required|in:user,service_provider',
        ]);

        try {
            if ($request->provider === 'apple') {
                // Choose credentials based on client_id
                $role = $request->role;

                if ($role === 'service_provider') {
                    $appleConfig = [
                        'client_id' => config('services.apple.app1.client_id'),
                        'key_id' => config('services.apple.app1.key_id'),
                        'private_key_path' => config('services.apple.app1.private_key'),
                    ];
                } elseif ($role === 'user') {
                    $appleConfig = [
                        'client_id' => config('services.apple.app2.client_id'),
                        'key_id' => config('services.apple.app2.key_id'),
                        'private_key_path' => config('services.apple.app2.private_key'),
                    ];
                } else {
                    return $this->error([], 'Invalid Apple client_id', 422);
                }

                // Generate client secret
                $privateKey = trim(file_get_contents($appleConfig['private_key_path']));
                $teamId = config('services.apple.team_id');

                $now = now()->timestamp;
                $exp = now()->addMonths(6)->timestamp;

                $payload = [
                    'iss' => $teamId,
                    'iat' => $now,
                    'exp' => $exp,
                    'aud' => 'https://appleid.apple.com',
                    'sub' => $appleConfig['client_id'],
                ];

                $clientSecret = JWT::encode($payload, $privateKey, 'ES256', $appleConfig['key_id']);

                // Reconfigure Socialite at runtime
                config([
                    'services.apple.client_id' => $appleConfig['client_id'],
                    'services.apple.client_secret' => $clientSecret,
                ]);
            }
            $socialUser = Socialite::driver($request->provider)->stateless()->userFromToken($request->token);

            if (!$socialUser || !$socialUser->getEmail()) {
                return $this->error([], "Unable to retrieve user Email", 400);
            }

            $email = $socialUser->getEmail();
            $user = User::where('email', $email)->first();
            $isNewUser = false;
            // if ($socialUser->getAvatar()) {
            //     $avatarUrl = $socialUser->getAvatar();
            //     $imageName = uploadGoogleImage($avatarUrl, 'User/Avatar'); // Custom function to handle URL images
            // } else {
            //     $imageName = 'default-avatar.png'; // Default avatar if Google does not provide one
            // }

            if (!$user) {
                $user = User::create([
                    'name'           => $request->input('name', $socialUser->getName() ?? 'Unknown User'),
                    'email'          => $email,
                    'password'       => bcrypt(Str::random(16)), // Random password
                    'role'           => $request->input('role'),
                    'agree_to_terms' => $request->input('agree_to_terms', 1), // Default to agreed
                    'avatar'  => null,
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

            // verify email if not verified for chating
            $user->update([
                'email_verified_at' => now(),
            ]);

            $message = $isNewUser ? 'User registered successfully' : 'User logged in successfully';

            // Generate JWT token

            $token = JWTAuth::fromUser($user);

            if ($user->role == $request->role) {
                $user->setAttribute('token', $token);
            } else {
                if ($user->role == 'user') {
                    return $this->error([], 'Your are not a service provider registered', 403);
                }
                if ($user->role == 'service_provider') {
                    return $this->error([], 'Your are not a customer registered', 403);
                }
            }


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
                        'avatar'            => $user->avatar ?? null,
                        'is_notices'        => $user->is_notices ?? true,
                        'is_messages'       => $user->is_messages ?? true,
                        'is_likes'          => $user->is_likes ?? true,
                        'safety_mode'       => $user->safety_mode ?? true,
                        'google_id'         => $user->google_id ?? null,
                        'apple_id'          => $user->apple_id ?? null,
                        'google_calendar_token' => $user->google_calendar_token ?? null,
                        'token'             => $token,
                    ],
                    'is_service_provider_info' => $user->role === 'service_provider' ? (bool) $user->serviceProviderProfile : true,
                ],
                'code'    => 200,
            ]);

            // return $this->success($response, $message, 200);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "Something went wrong", 500);
        }
    }
}
