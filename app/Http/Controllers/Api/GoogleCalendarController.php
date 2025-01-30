<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Google\Client;
use Google\Service\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GoogleCalendarController extends Controller
{
    private function getGoogleClient()
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->addScope(Calendar::CALENDAR_EVENTS);
        $client->setAccessType('offline');

        return $client;
    }

    // Redirect to Google OAuth
    public function redirectToGoogle()
    {
        $client = $this->getGoogleClient();
        return redirect($client->createAuthUrl());
    }

    // Handle OAuth Callback and Store Access Token
    public function handleGoogleCallback(Request $request)
    {
        $client = $this->getGoogleClient();
        $token = $client->fetchAccessTokenWithAuthCode($request->input('code'));

        if (isset($token['error'])) {
            return response()->json(['error' => $token['error']], 400);
        }

        Session::put('google_access_token', $token['access_token']);
        return response()->json(['message' => 'Authenticated successfully', 'access_token' => $token['access_token']]);
    }

    // Add Booking to Google Calendar
    public function addBookingToCalendar(Request $request)
    {
        $request->validate([
            'access_token' => 'required',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);

        $client = $this->getGoogleClient();
        $client->setAccessToken($request->access_token);
        $calendarService = new Calendar($client);

        $event = new Calendar\Event([
            'summary' => $request->title,
            'description' => $request->description,
            'start' => ['dateTime' => $request->start_time, 'timeZone' => 'Asia/Dhaka'],
            'end' => ['dateTime' => $request->end_time, 'timeZone' => 'Asia/Dhaka'],
        ]);

        $calendarService->events->insert('primary', $event);

        return response()->json(['message' => 'Event added to Google Calendar']);
    }
}
