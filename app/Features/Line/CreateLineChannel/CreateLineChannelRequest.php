<?php

namespace App\Features\Line\CreateLineChannel;

use Illuminate\Foundation\Http\FormRequest;

class CreateLineChannelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_website_id' => ['required', 'integer', 'min:1'],
            'client_id' => ['required', 'string'],
            'client_secret' => ['required', 'string'],
            'access_token' => ['required', 'string'],
            'channel_name' => ['nullable', 'string'],
        ];
    }
}
