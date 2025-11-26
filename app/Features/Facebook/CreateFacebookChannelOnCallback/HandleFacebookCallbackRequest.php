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
            // OAuth authorization code from Facebook. Required if no error.
            'code' => ['required_without:error', 'string'],
            // State parameter passed during OAuth flow.
            'state' => ['required', 'string'],
            // Error code if OAuth authorization failed.
            'error' => ['sometimes', 'string'],
            // Error description if OAuth authorization failed.
            'error_description' => ['sometimes', 'string'],
        ];
    }
}
