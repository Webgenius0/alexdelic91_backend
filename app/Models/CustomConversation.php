<?php

namespace App\Models;

use Namu\WireChat\Models\Conversation as WireChatConversation;

class CustomConversation extends WireChatConversation
{
    protected $fillable = [
        'chat_type',
    ];
}
