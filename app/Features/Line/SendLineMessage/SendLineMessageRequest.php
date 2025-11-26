<?php

namespace App\Features\Line\SendLineMessage;

use Illuminate\Foundation\Http\FormRequest;

class SendLineMessageRequest extends FormRequest
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
            'text' => ['required_if:type,text', 'string', 'min:1'],
            // Optional conversation ID to associate the message with.
            'conversation_id' => ['nullable', 'integer', 'exists:conversations,id'],
            // Optional agent ID who is sending the message.
            'agent_id' => ['nullable', 'integer', 'exists:agents,id'],
            // Optional LINE reply token for replying to a specific message.
            'reply_token' => ['nullable', 'string'],
            // Optional ID of the message this is replying to.
            'reply_to_id' => ['nullable', 'string'],
        ];
    }
}
