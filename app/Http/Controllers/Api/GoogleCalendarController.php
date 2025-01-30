<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Illuminate\Support\Facades\Auth;

class GoogleCalendarController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->client->setAuthConfig(storage_path('app/google-calendar/credentials.json'));
        $this->client->addScope(Calendar::CALENDAR);
        $this->client->setRedirectUri(route('google-calendar/callback'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');
    }

    // Redirect to Google OAuth
    public function redirectToGoogle()
    {
        $authUrl = $this->client->createAuthUrl();
        return response()->json(['auth_url' => $authUrl]);
    }

    // Handle Google OAuth callback
    public function handleGoogleCallback(Request $request)
    {
        $code = $request->get('code');
        $token = $this->client->fetchAccessTokenWithAuthCode($code);

        if (isset($token['error'])) {
            return response()->json(['error' => 'Failed to authenticate with Google.'], 400);
        }

        // Save the token to the database
        Auth::user()->update(['google_calendar_token' => json_encode($token)]);

        return response()->json(['success' => 'Successfully authenticated with Google.']);
    }

    // Sync events with Google Calendar
    public function syncEvents(Request $request)
    {
        $user = Auth::user();
        $token = json_decode($user->google_calendar_token, true);

        if (!$token) {
            return response()->json(['error' => 'User not authenticated with Google.'], 401);
        }

        $this->client->setAccessToken($token);

        if ($this->client->isAccessTokenExpired()) {
            $refreshToken = $this->client->getRefreshToken();
            $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
            $newToken = $this->client->getAccessToken();
            Auth::user()->update(['google_calendar_token' => json_encode($newToken)]);
        }

        // Fetch bookings from your existing API
        $bookings = $this->getUserBookings($user->email);

        // Sync bookings to Google Calendar
        $service = new Calendar($this->client);
        foreach ($bookings as $booking) {
            $event = new Event([
                'summary' => $booking['service_name'],
                'start' => ['dateTime' => $booking['start_time'], 'timeZone' => 'UTC'],
                'end' => ['dateTime' => $booking['end_time'], 'timeZone' => 'UTC'],
            ]);

            $service->events->insert('primary', $event);
        }

        return response()->json(['success' => 'Events synced successfully.']);
    }

    // Fetch bookings from your existing API
    private function getUserBookings($email)
    {
        // Call your existing API to fetch bookings
        return [
            [
                'service_name' => 'Booking 1',
                'start_time' => '2023-10-15T09:00:00Z',
                'end_time' => '2023-10-15T10:00:00Z',
            ],
        ];
    }

}
