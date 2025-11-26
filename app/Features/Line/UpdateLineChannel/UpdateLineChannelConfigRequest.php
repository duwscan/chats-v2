<?php

namespace App\Features\Line\UpdateLineChannel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLineChannelConfigRequest extends FormRequest
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

    public function routeConfigId(): int
    {
        return (int) $this->route('configId');
    }
}
