<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Models\CustomConversation;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Namu\WireChat\Events\MessageCreated;
use Namu\WireChat\Events\NotifyParticipant;
use Namu\WireChat\Models\Conversation;

class ChatController extends Controller
{
    use ApiResponse;

    // get all chats
    public function chats(Request $request)
    {
        // Validate the 'chat_type' parameter (optional, and can be null)
        $validator = Validator::make($request->all(), [
            'chat_type' => 'nullable|in:direct,job_post',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors(),$validator->errors()->first(), 400); // Return error if validation fails
        }


        $user = auth()->user();
        $chatQuery = $user->conversations()
            ->with(['participants' => function ($query) use ($user) {
                $query->with('participantable:id,name,avatar')->where('participantable_id', '!=', auth()->id());

            }, 'lastMessage']);

        // Apply chat_type filter if provided, including NULL
        if ($request->has('chat_type')) {
            $chatQuery->where('chat_type', $request->chat_type);
        }

        $chat = $chatQuery->get();


        return $this->success($chat, "Chat list fetch successfully", 200);
    }
    // get single chat details
    public function chat($user_id)
    {
        // Find the user by ID
        $user = User::find($user_id);
        if (!$user) {
            return $this->error([], "User not found", 404);
        }

        // Get pagination parameters from the request
        $perPage = request()->get('per_page', 100);
        $page = request()->get('page', 1);

        // Fetch the conversation with the specified user (make sure to load participants)
        $conversation = auth()->user()->conversations()
            ->whereHas('participants', function ($query) use ($user) {
                $query->where('participantable_id', $user->id);
            })
            ->first();

        if (!$conversation) {
            $auth = auth()->user();
            $conversation = $auth->createConversationWith($user );
//            return $this->success([], "New Conversation created", 201);
            return response()->json([
                'success' => true,
                'message' => "New Conversation created",
                'conversation_id' => $conversation->id,
                'data' => [],
                'code' => 201
            ], 201);
        }
        // Paginate the messages for the found conversation
        $messages = $conversation->messages()
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);

        // Load the participants and any other necessary relationships
        $conversation->load([
            'participants' => function ($query) {
                $query->with('participantable:id,name,avatar');
            }
        ]);

        // Return success response with the conversation and messages
//        return $this->success(, "Chat fetched successfully", 200);

        return response()->json([
            'success' => true,
            'message' => "Chat fetched successfully",
            'conversation_id' => $conversation->id,
            'data' => ['conversation' => $conversation,'messages' => $messages],
            'code' => 200
        ], 200);
    }



// send message
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'message' => 'required_without|string',
            'file' => 'required_without:message|file|mimes:png,jpg,jpeg,webp|max:2048',
            'chat_type' => 'required|string|in:direct,job_post',
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

                //save the conversation type for showing specific messages
                if ($request->chat_type == 'job_post'){

                    $conversation = CustomConversation::find($chat->conversation_id); //custom conversation extend the wire chat conversation
                    if ($conversation){
                        $conversation->update([
                            'chat_type' => 'job_post'
                        ]);

                    }
                }
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
