<?php
namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;

class NotificationController extends Controller
{

    use ApiResponse;

    public function getNotifications()
    {
        $user = auth()->user();

        $data = $user->notifications()
            ->select('id', 'data', 'read_at', 'created_at')
            ->latest()
            ->get();

        if ($data->isEmpty()) {
            return $this->error([], 'No Notifications Found', 200);
        }

        return $this->success($data, 'Notifications fetched successfully', 200);
    }
}
