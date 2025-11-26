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
            'type' => ['required', 'string', 'in:text'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'text' => ['required_if:type,text', 'string', 'min:1'],
            'conversation_id' => ['nullable', 'integer', 'exists:conversations,id'],
            'agent_id' => ['nullable', 'integer', 'exists:agents,id'],
            'reply_token' => ['nullable', 'string'],
            'reply_to_id' => ['nullable', 'string'],
        ];
    }
}
