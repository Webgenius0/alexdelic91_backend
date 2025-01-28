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

        if (! $user) {
            return $this->error([], "User Unauthorized", 404);
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
                return $this->error([], "Something went wrong", 404);
            }
            return $this->success($data, "Bookmarked Successfully", 200);
        }
    }

    public function getBookmarks()
    {
        $user = auth()->user();

        if (! $user) {
            return $this->error([], "User Unauthorized", 401);
        }

        $bookmarks = BookMark::with(['serviceProvider.ServiceProviderProfile.serviceProviderImage'])
            ->where('user_id', $user->id)
            ->get();

        if ($bookmarks->isEmpty()) {
            return $this->error([], "No Bookmarks Found", 404);
        }
        return $this->success($bookmarks, "Bookmarks Found Successfully", 200);
    }

}
