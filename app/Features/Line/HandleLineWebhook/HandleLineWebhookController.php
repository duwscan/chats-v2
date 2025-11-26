<?php

namespace App\Features\Line\HandleLineWebhook;

use App\Exceptions\CustomException;
use App\Features\Line\HandleLineWebhook\Actions\CreateLineMessageAction;
use App\Features\Line\HandleLineWebhook\Actions\UpsertLineConversationAction;
use App\Features\Line\HandleLineWebhook\Actions\UpsertLineCustomerAction;
use App\Features\Line\HandleLineWebhook\Actions\VerifyLineSignatureAction;
use App\Features\Line\LineChannel;
use App\Http\Controllers\ApiResponseTrait;
use App\Models\ChannelWebhookConfig;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class HandleLineWebhookController
{
    use ApiResponseTrait;

    public function __invoke(
        HandleLineWebhookRequest $request,
        VerifyLineSignatureAction $verifyLineSignatureAction,
        UpsertLineCustomerAction $upsertLineCustomerAction,
        UpsertLineConversationAction $upsertLineConversationAction,
        CreateLineMessageAction $createLineMessageAction,
    ): JsonResponse {
        $userWebsiteId = $request->routeUserWebsiteId();
        $configId = $request->routeConfigId();

        $config = ChannelWebhookConfig::query()
            ->where('id', $configId)
            ->where('user_website_id', $userWebsiteId)
            ->where('channel', 'line')
            ->first();

        if (! $config) {
            throw new CustomException('LINE config not found.', 404);
        }

        $channelConfig = LineChannel::fromArray($config->config ?? []);
        $verifyLineSignatureAction->execute($request, $channelConfig->getClientSecret());

        $events = collect($request->json('events', []))
            ->map(fn (array $event) => LineWebhookEvent::fromArray($event))
            ->filter()
            ->values();

        foreach ($events as $event) {
            Log::info('line.webhook.event', $event->toArray());
            $customer = $upsertLineCustomerAction->execute($event, $userWebsiteId, $channelConfig->getAccessToken());
            $conversation = $upsertLineConversationAction->execute($customer);
            $createLineMessageAction->execute($conversation, $customer, $event, $config->id);
        }

        return $this->responseSuccess(new LineWebhookResultResource($events->count()));
    }
}
