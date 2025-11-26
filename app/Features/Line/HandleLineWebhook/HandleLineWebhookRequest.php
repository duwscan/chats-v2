<?php

namespace App\Features\Line\HandleLineWebhook;

use Illuminate\Foundation\Http\FormRequest;

class HandleLineWebhookRequest extends FormRequest
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

    public function validationData(): array
    {
        return array_merge(parent::validationData(), [
            'userWebsiteId' => $this->route('userWebsiteId'),
            'configId' => $this->route('configId'),
        ]);
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
