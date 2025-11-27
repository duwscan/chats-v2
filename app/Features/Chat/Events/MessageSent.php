<?php

namespace App\Features\Chat\Events;

use App\Models\ConversationModel;
use App\Models\CustomerModel;
use App\Models\MessageModel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly MessageModel $message,
        public readonly ConversationModel $conversation,
        public readonly CustomerModel $customer,
        public readonly string $channel,
    ) {}
}
