<?php

namespace App\Features\Facebook\HandleWebhook\Actions;

use App\Exceptions\CustomException;
use Illuminate\Http\Request;

class VerifyFacebookSignatureAction
{
    public function execute(Request $request, string $appSecret): void
    {
        if (empty($appSecret)) {
            throw new CustomException('Facebook app secret not configured.');
        }

        $body = $request->getContent();
        $expectedSignature = 'sha256='.hash_hmac('sha256', $body, $appSecret);
        $signatureHeader = $request->header('X-Hub-Signature-256');

        if (! hash_equals($expectedSignature, (string) $signatureHeader)) {
            throw new CustomException('Invalid Facebook signature.', 403);
        }
    }
}
