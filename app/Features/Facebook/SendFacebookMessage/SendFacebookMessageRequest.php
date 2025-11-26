<?php

namespace App\Features\Facebook\SendFacebookMessage;

use App\Features\Common\Rules\ConversationBelongsToCustomer;
use Illuminate\Foundation\Http\FormRequest;

class SendFacebookMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Message type. Currently only 'text' is supported.
            'type' => ['required', 'string', 'in:text'],
            // The ID of the customer to send the message to.
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            // The message text content.
            'text' => ['required', 'string', 'min:1'],
            // Optional agent ID who is sending the message.
            'agent_id' => ['nullable', 'integer', 'exists:agents,id'],
            // Optional ID of the message this is replying to.
            'reply_to_id' => ['nullable', 'string'],
            // Optional conversation ID to associate the message with. Must belong to the customer.
            'conversation_id' => [
                'nullable',
                'integer',
                'exists:conversations,id',
                new ConversationBelongsToCustomer,
            ],
        ];
    }
}
