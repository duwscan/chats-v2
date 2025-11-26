<?php

namespace App\Features\Facebook\HandleWebhook;

use Illuminate\Foundation\Http\FormRequest;

class HandleFacebookWebhookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'userWebsiteId' => ['required', 'integer', 'min:1'],
            'configId' => ['required', 'integer', 'min:1'],
        ];
    }

    public function routeUserWebsiteId(): int
    {
        return (int) $this->route('userWebsiteId');
    }

    public function routeConfigId(): int
    {
        return (int) $this->route('configId');
    }
}
