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
            // The ID of the user website this channel belongs to.
            'user_website_id' => ['required', 'integer', 'min:1'],
            // LINE channel client ID.
            'client_id' => ['required', 'string'],
            // LINE channel client secret.
            'client_secret' => ['required', 'string'],
            // LINE channel access token.
            'access_token' => ['required', 'string'],
            // Optional custom name for the channel.
            'channel_name' => ['nullable', 'string'],
        ];
    }
}
