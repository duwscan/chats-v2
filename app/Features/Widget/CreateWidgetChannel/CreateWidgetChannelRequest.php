<?php

namespace App\Features\Widget\CreateWidgetChannel;

use Illuminate\Foundation\Http\FormRequest;

class CreateWidgetChannelRequest extends FormRequest
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
            // Optional widget ID.
            'widget_id' => ['nullable', 'string'],
            // Optional custom name for the widget channel.
            'widget_name' => ['nullable', 'string'],
        ];
    }
}
