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
        $authUser = auth()->user();
        $chatType = $request->get('chat_type', 'direct');
        $jobPostId = $request->get('job_post_id');
        $perPage = $request->get('per_page', 1000);
        $page = $request->get('page', 1);

        $user = User::find($user_id);
        if (!$user) {
            return $this->error([], "User not found", 404);
        }

        $jobPost = null;
        if ($chatType === 'job_post' && $jobPostId) {
            $jobPost = JobPost::with([
                'category:id,category_name',
                'subcategory:id,subcategory_name',
                'user',
            ])->find($jobPostId);

            if (!$jobPost) {
                return $this->error([], "Job post not found", 404);
            }
        }

        // Fetch conversation if it exists
        $conversationQuery = $authUser->conversations()
            ->whereHas('participants', function ($query) use ($user) {
                $query->where('participantable_id', $user->id);
            })
            ->where('chat_type', $chatType);

        if ($chatType === 'job_post' && $jobPostId) {
            $conversationQuery->where('job_post_id', $jobPostId);
        }

        $conversation = $conversationQuery->first();

        if (!$conversation) {
            return  $this->error([], "Conversation not found", 200);
            if ($chatType === 'job_post' && $jobPost) {
                $conversation = $authUser->createGroup($jobPost->title . '-' . $user->name);
                $conversation->update([
                    'chat_type' => 'job_post',
                    'job_post_id' => $jobPostId,
                ]);
            } else {
                $conversation = $authUser->createConversationWith($user);
            }

            return response()->json([
                'success' => true,
                'message' => "New conversation created",
                'conversation_id' => $conversation->id,
                'job_post' => $jobPost,
                'data' => null,
                'code' => 201
            ], 201);
        }

        // Load messages and participants
        $conversation->load([
            'messages' => function ($query) use ($perPage, $page) {
                $query->with('attachment')->latest()->paginate($perPage, ['*'], 'page', $page);
            },
            'participants' => function ($query) {
                $query->with('participantable');
            }
        ]);

        return response()->json([
            'success' => true,
            'message' => "Chat fetched successfully",
            'conversation_id' => $conversation->id,
            'job_post' => $jobPost,
            'data' => ['conversation' => $conversation],
            'code' => 200
        ], 200);
    }




// send message
    public function sendMessage(Request $request)
    {
        $mediaMimes = config('wirechat.attachments.media_mimes', []);
        $fileMimes = config('wirechat.attachments.file_mimes', []);
        $maxUploadSize = max(
            config('wirechat.attachments.media_max_upload_size', 1024),
            config('wirechat.attachments.file_max_upload_size', 1024)
        );

        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'exists:users,id'],
            'message' => ['required_without:file', 'string'],
            'file' => [
                'required_without:message',
                'file',
                'mimes:' . implode(',', array_merge($mediaMimes, $fileMimes)),
                'max:' . $maxUploadSize,
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

            if (!$toUser || $formUser->id === $toUser->id) {
                return $this->error([], "Invalid recipient", 404);
            }

            $conversation = null;

            if ($request->chat_type === 'job_post') {
                $jobPost = JobPost::findOrFail($request->job_post_id);

                $conversation = $formUser->conversations()
                    ->where('chat_type', 'job_post')
                    ->where('job_post_id', $jobPost->id)
                    ->where('type', 'group')
                    ->first();

                if (!$conversation) {
                    $conversation = $formUser->createGroup($jobPost->title . '-' . $toUser->name);
                    $conversation->chat_type = 'job_post';
                    $conversation->job_post_id = $jobPost->id;
                    $conversation->save();

                }
            } else {
                $conversation = $formUser->hasConversationWith($toUser)
                    ? $formUser->getConversationWith($toUser)
                    : $formUser->createConversationWith($toUser);
            }

            // Handle file or text message
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $size = $file->getSize();
                $path = uploadImage($file, 'chat');

                $chat = $conversation->messages()->create([
                    'sendable_type' => get_class($formUser),
                    'sendable_id' => $formUser->id,
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
            } else {
                $chat = $conversation->messages()->create([
                    'body' => $request->message,
                    'sendable_type' => get_class($formUser),
                    'sendable_id' => $formUser->id,
                    'type' => MessageType::TEXT,
                ]);
            }

            broadcast(new MessageCreated($chat));
            $participant = $chat->conversation->participant($toUser);
            if ($participant) {
                broadcast(new NotifyParticipant($participant, $chat));
            }


            $chat->conversation->load([
                'messages' => fn ($q) => $q->with('attachment')->latest()->limit(1),
                'participants.participantable'
            ]);

            DB::commit();
            return $this->success($chat, "Message sent successfully", 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage(), 500);
        }
    }


}
