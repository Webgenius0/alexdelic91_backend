<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\BookMark;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class BookMarkController extends Controller
{
    use ApiResponse;

    public function store(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->error([], "User Unauthorized", 401);
        }

        $existingBookmark = BookMark::where('user_id', $user->id)
            ->where('service_provider_id', $id)
            ->first();

        if ($existingBookmark) {

            $existingBookmark->delete();
            return $this->success([], "Bookmark Removed Successfully", 200);
        } else {

            $data = BookMark::create([
                'user_id'             => $user->id,
                'service_provider_id' => $id,
            ]);

            if (! $data) {
                return $this->error([], "Something went wrong", 200);
            }
            return $this->success($data, "Bookmarked Successfully", 200);
        }
    }

    public function getBookmarks()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->error([], "User Unauthorized", 401);
        }

        $bookmarks = BookMark::with([
            'serviceProvider.serviceProviderProfile' => function ($query) {
                $query->select('id', 'user_id', 'business_name', 'address', 'latitude', 'longitude');
            },
            'serviceProvider.serviceProviderProfile.serviceProviderImage:id,service_provider_id,images',
            'serviceProvider.bookings.feedbacks:id,service_provider_id,booking_id,rating',
        ])
            ->where('user_id', $user->id)
            ->get()
            ->map(function ($bookmark) {
                // Get feedbacks for the specific service provider
                $feedbacks = $bookmark->user->bookings->flatMap->feedbacks->where('booking.service_provider_id', $bookmark->service_provider_id);

                // Calculate the average rating for the specific service provider
                $averageRating = $feedbacks->isNotEmpty()
                    ? round($feedbacks->avg('rating'), 2)
                    : 0;

                $bookmark->average_rating = $averageRating;

                return [
                    'bookmark_id'         => $bookmark->id,
                    'user_id'             => $bookmark->user_id,
                    'service_provider_id' => $bookmark->service_provider_id,
                    'service_provider'    => $bookmark->serviceProvider->serviceProviderProfile,
                    'average_rating'      => $averageRating,
                ];
            });

        if ($bookmarks->isEmpty()) {
            return $this->error([], "No Bookmarks Found", 200);
        }

        return $this->success($bookmarks, "Bookmarks Found Successfully", 200);
    }
}
