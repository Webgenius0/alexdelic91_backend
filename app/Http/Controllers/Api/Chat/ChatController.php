<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Namu\WireChat\Events\MessageCreated;
use Namu\WireChat\Events\NotifyParticipant;

class ChatController extends Controller
{
    use ApiResponse;

    // get all chats
    public function chats()
    {

        $user = auth()->user();
        $chat = $user->conversations()
            ->with(['participants' => function ($query) use ($user) {
                $query->with('participantable:id,name,avatar')->where('participantable_id', '!=', auth()->id());

            }, 'lastMessage'])
            ->get();

        return $this->success($chat, "Chat list fetch successfully", 200);
    }
    // get single chat details
    public function chat($user_id)
    {
        $user = User::find($user_id);
        if (!$user) {
            return $this->error([], "User not found", 404);
        }
        // Get pagination parameters from the request
        $perPage = request()->get('per_page', 100);
        $page = request()->get('page', 1);

        // Paginate messages (using dynamic pagination parameters)
        $chat = $user->conversations()
            ->with(['participants' => function ($query) use ($user) {
                $query->with('participantable:id,name,avatar');

            }, 'messages' => function ($query) use ($perPage) {
                $query->latest()->paginate($perPage);
            }])
            ->first();

        return $this->success($chat??[], "Chat fetch successfully ", 200);
    }


// send message
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'message' => 'required_without|string',
            'file' => 'required_without:message|file|mimes:png,jpg,jpeg,webp|max:2048',
        ]);
        if ($validator->fails()) {
           return $this->error($validator->errors(), "Validation Error", 422);
        }
        DB::beginTransaction();
        try {
            $formUser = auth()->user();
            $toUser = User::find($request->user_id);
            if ($formUser->id == $toUser->id) {
                return $this->error([], "You can't chat with yourself", 404);
            }
            $message = $request->message;
            if ($formUser && $toUser) {
                if($request->hasFile('file') && $request->file('file')->isValid() && $request->message == null){
                    $message= uploadImage($request->file('file'), 'chat',);
                }
                $chat = $formUser->sendMessageTo($toUser, $message);

                // Broadcast events after successful message creation
                broadcast(new MessageCreated($chat));
                broadcast(new NotifyParticipant($chat->conversation->participant($toUser), $chat));

                DB::commit();
                return $this->success($chat??[], "Message sent successfully", 200);
            }

            return $this->error([], "User not found", 404);
        }catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage(), 500);
        }
    }


}
