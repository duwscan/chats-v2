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
            'type' => ['required', 'string', 'in:text'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'text' => ['required', 'string', 'min:1'],
            'agent_id' => ['nullable', 'integer', 'exists:agents,id'],
            'reply_to_id' => ['nullable', 'string'],
            'conversation_id' => [
                'nullable',
                'integer',
                'exists:conversations,id',
                new ConversationBelongsToCustomer,
            ],
        ];
    }
}
