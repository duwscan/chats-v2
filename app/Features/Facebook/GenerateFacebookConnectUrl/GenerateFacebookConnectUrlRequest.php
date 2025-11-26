<?php

namespace App\Features\Facebook\GenerateFacebookConnectUrl;

use Illuminate\Foundation\Http\FormRequest;

class GenerateFacebookConnectUrlRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // The ID of the user website to generate OAuth URL for.
            'userWebsiteId' => ['required', 'integer', 'min:1'],
        ];
    }

    public function routeParamUserWebsiteId(): int
    {
        /** @var int $id */
        $id = (int) $this->route('userWebsiteId');

        return $id;
    }
}
