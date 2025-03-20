<?php

namespace App\Http\Controllers\Api\Provider;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NotificationSettingController extends Controller
{
    use ApiResponse;

    public function notificationsSetting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'is_notices' => 'nullable|boolean',
            'is_messages' => 'nullable|boolean',
            'is_likes' => 'nullable|boolean',
            'safety_mode' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }
        $user = auth()->user();

        $user->update([
            'is_notices' => $request->is_notices,
            'is_messages' => $request->is_messages,
            'is_likes' => $request->is_likes,
            'safety_mode' => $request->safety_mode
        ]);

        return $this->success($user, 'Notification settings updated successfully', 200);
    }

    public function getNotificationsSetting()
    {
        $user = auth()->user();

        $data = User::where('id', $user->id)->select('is_notices', 'is_messages', 'is_likes', 'safety_mode')->first();

        if(! $data) {
            return $this->error([], 'User Not Found', 200);
        }

        return $this->success($data, 'Notification settings', 200);
    }
}
