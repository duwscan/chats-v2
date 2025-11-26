<?php

namespace App\Features\Facebook\CreateFacebookChannelOnCallback;

use Illuminate\Foundation\Http\FormRequest;

class HandleFacebookCallbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required_without:error', 'string'],
            'state' => ['required', 'string'],
            'error' => ['sometimes', 'string'],
            'error_description' => ['sometimes', 'string'],
        ];
    }
}
