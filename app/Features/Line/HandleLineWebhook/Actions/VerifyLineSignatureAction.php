<?php

namespace App\Features\Line\HandleLineWebhook\Actions;

use App\Exceptions\CustomException;
use Illuminate\Http\Request;
use LINE\Parser\EventRequestParser;
use LINE\Parser\Exception\InvalidEventRequestException;
use LINE\Parser\Exception\InvalidSignatureException;

class VerifyLineSignatureAction
{
    public function __construct(
        private readonly EventRequestParser $eventRequestParser,
    ) {}

    public function execute(Request $request, string $channelSecret): array
    {
        if ($channelSecret === '') {
            throw new CustomException('LINE channel secret is missing.');
        }

        $signatureHeader = $request->header('X-Line-Signature');
        $body = $request->getContent();

        if ($signatureHeader === null || $signatureHeader === '') {
            throw new CustomException('Missing LINE signature header.', 403);
        }

        try {
            $parsedEvents = $this->eventRequestParser->parseEventRequest(
                $body,
                $channelSecret,
                (string) $signatureHeader,
            );
        } catch (InvalidSignatureException $e) {
            throw new CustomException('Invalid LINE signature.', 403);
        } catch (InvalidEventRequestException $e) {
            throw new CustomException('Invalid LINE event payload.', 400);
        }

        return $parsedEvents->getEvents();
    }
}
