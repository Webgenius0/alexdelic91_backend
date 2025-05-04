<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Models\CustomConversation;
use App\Models\JobPost;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Namu\WireChat\Enums\MessageType;
use Namu\WireChat\Events\MessageCreated;
use Namu\WireChat\Events\NotifyParticipant;
use Namu\WireChat\Models\Conversation;
use Namu\WireChat\Models\Message;

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
    public function chat($user_id, Request $request)
    {

        // Find the user by ID
        $user = User::find($user_id);
        if (!$user) {
            return $this->error([], "User not found", 404);
        }

        // Get pagination parameters from the request
        $perPage = request()->get('per_page', 1000);
        $page = request()->get('page', 1);

        //job post
        $job_post_id = $request->get('job_post_id');
        if ($job_post_id) {
            $jobPost = JobPost::with([
                'category:id,category_name',
                'subcategory:id,subcategory_name',
                'user',
            ])->find($job_post_id);
        }

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
                'job_post' => $jobPost ?? null,
                'data' => null,
                'code' => 201
            ], 201);
        }
        // Load the participants and any other necessary relationships
        $conversation->load([
            'messages' => function ($query) use ($perPage, $page) {
                $query->with(['attachment'])->latest()->paginate($perPage, ['*'], 'page', $page);
            },
            'participants' => function ($query) {
                $query->with('participantable');
            }
        ]);


        // Return success response with the conversation and messages
//        return $this->success(, "Chat fetched successfully", 200);

        return response()->json([
            'success' => true,
            'message' => "Chat fetched successfully",
            'conversation_id' => $conversation->id,
            'job_post' => $jobPost ?? null,
            'data' => ['conversation' => $conversation],
            'code' => 200
        ], 200);
    }



// send message
    public function sendMessage(Request $request)
    {
        // Get MIME types and max size from config
        $mediaMimes = config('wirechat.attachments.media_mimes', []);
        $fileMimes = config('wirechat.attachments.file_mimes', []);
        $maxUploadSize = max(
            config('wirechat.attachments.media_max_upload_size', 1024),
            config('wirechat.attachments.file_max_upload_size', 1024)
        );

// Validation
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'exists:users,id'],
            'message' => ['required_without:file', 'string'],
            'file' => [
                'required_without:message',
                'file',
                'mimes:' . implode(',', array_merge($mediaMimes, $fileMimes)),
                'max:' . $maxUploadSize, // In kilobytes, so 12MB = 12288
            ],
            'chat_type' => ['required', 'string', 'in:direct,job_post'],
            'job_post_id' => ['required_if:chat_type,job_post', 'exists:job_posts,id'],
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
            if ($formUser && $toUser) {

                if ($request->hasFile('file') && $request->file('file')->isValid() && $request->message == null) {
                    $file = $request->file('file');
                    $extension = $file->getClientOriginalExtension();
                    $size = $file->getSize();

                    $path = uploadImage($file, 'chat'); // This likely moves the file

                    $conversation = $formUser->getConversationWith($toUser); // assume this returns or creates the conversation
                    if (!$conversation) {
                        $conversation = $formUser->createConversationWith($toUser);
                    }

                    $chat = $conversation->messages()->create([
                        'sendable_type' => get_class(auth()->user()),
                        'sendable_id' => auth()->id(),
                        'type' => MessageType::ATTACHMENT,
                    ]);


                    $chat->attachment()->create([
                        'file_path' => $path,
                        'file_name' => basename($path),
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getClientMimeType(),
                        'url' => Storage::url($path),
                        'type' => $extension,
                        'size' => $size,
                    ]);
                }else{
                    $message = $request->message;
                    $chat = $formUser->sendMessageTo($toUser, $message);

                }
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
                $chat->conversation->load([
                    'messages' => function ($query) {
                        $query->with(['attachment'])->latest()->limit(1);
                    },
                    'participants' => function ($query) {
                        $query->with('participantable');
                    }
                ]);
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
