<?php

namespace App\Features\Widget\UpdateAppAdapterChannel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppAdapterChannelRequest extends FormRequest
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

    public function routeConfigId(): int
    {
        return (int) $this->route('configId');
    }
}
