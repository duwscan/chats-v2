<?php

namespace App\Features\Widget\CreateAppAdapterChannel;

use Illuminate\Foundation\Http\FormRequest;

class CreateAppAdapterChannelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_website_id' => ['required', 'integer', 'min:1'],
            'domain_name' => ['required', 'string'],
        ];
    }
}
