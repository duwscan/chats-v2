<?php

namespace App\Features\Widget\HandleWebhook;

use Illuminate\Foundation\Http\FormRequest;

class HandleWidgetWebhookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // The visitor ID from the widget.
            'visitor_id' => ['required', 'string'],
            // The message text content.
            'message' => ['required', 'string', 'min:1'],
            // Optional message ID.
            'message_id' => ['nullable', 'string'],
            // Optional metadata.
            'metadata' => ['nullable', 'array'],
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
