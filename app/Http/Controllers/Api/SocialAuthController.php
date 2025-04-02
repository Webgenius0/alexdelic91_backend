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

            $response = [
                'status'     => true,
                'message'    => $isNewUser ? 'User registered successfully' : 'User logged in successfully',
                'token_type' => 'bearer',
                'token'      => $token,
                'data'       => [
                    'id'             => $user->id,
                    'name'           => $user->name,
                    'email'          => $user->email,
                    'role'           => $user->role,
                    'agree_to_terms' => $user->agree_to_terms,
                    'avatar'         => $user->avatar,
                ],
            ];

            return $this->success($response, $message, 200);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "Something went wrong", 500);
        }
    }
}
