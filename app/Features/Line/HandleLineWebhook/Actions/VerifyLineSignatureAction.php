<?php

namespace App\Features\Line\HandleLineWebhook\Actions;

use App\Exceptions\CustomException;
use Illuminate\Http\Request;

class VerifyLineSignatureAction
{
    public function execute(Request $request, string $channelSecret): void
    {
        if ($channelSecret === '') {
            throw new CustomException('LINE channel secret is missing.');
        }

        $signatureHeader = $request->header('X-Line-Signature');
        $body = $request->getContent();
        $computedSignature = base64_encode(hash_hmac('sha256', $body, $channelSecret, true));

        if (! hash_equals($computedSignature, (string) $signatureHeader)) {
            throw new CustomException('Invalid LINE signature.', 403);
        }
    }
}
