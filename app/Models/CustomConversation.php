<?php

namespace App\Models;

use Namu\WireChat\Models\Conversation as WireChatConversation;

class CustomConversation extends WireChatConversation
{
    protected $fillable = [
        'chat_type',
        'job_post_id'

    ];

    protected $casts = [
        'chat_type' => 'string',
        'job_post_id' => 'integer',
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }
}
