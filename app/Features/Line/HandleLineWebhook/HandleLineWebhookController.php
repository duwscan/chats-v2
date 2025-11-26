<?php

namespace App\Features\Line\HandleLineWebhook;

use App\Exceptions\CustomException;
use App\Features\Line\HandleLineWebhook\Actions\CreateLineAttachmentMessageAction;
use App\Features\Line\HandleLineWebhook\Actions\CreateLineMessageAction;
use App\Features\Line\HandleLineWebhook\Actions\UpsertLineConversationAction;
use App\Features\Line\HandleLineWebhook\Actions\UpsertLineCustomerAction;
use App\Features\Line\HandleLineWebhook\Actions\VerifyLineSignatureAction;
use App\Features\Line\LineChannel;
use App\Http\Controllers\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\ChannelWebhookConfig;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use LINE\Webhook\Model\MessageEvent;

#[Group('LINE Webhooks')]
class HandleLineWebhookController extends Controller
{
    use ApiResponseTrait;

    /**
     * Handle incoming LINE webhook events.
     *
     * @response LineWebhookResultResource
     *
     * @status 200
     */
    public function __invoke(
        HandleLineWebhookRequest $request,
        VerifyLineSignatureAction $verifyLineSignatureAction,
        UpsertLineCustomerAction $upsertLineCustomerAction,
        UpsertLineConversationAction $upsertLineConversationAction,
        CreateLineMessageAction $createLineMessageAction,
        CreateLineAttachmentMessageAction $createLineAttachmentMessageAction,
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
        $sdkEvents = $verifyLineSignatureAction->execute($request, $channelConfig->getClientSecret());

        $events = collect($sdkEvents)
            ->filter(fn ($event) => $event instanceof MessageEvent)
            ->values();

        if ($events->isEmpty()) {
            Log::info('line.webhook.verification_request', [
                'user_website_id' => $userWebsiteId,
                'config_id' => $configId,
            ]);
        } else {
            foreach ($events as $event) {
                $message = $event->getMessage();
                $messageType = $message->getType();

                if (! in_array($messageType, ['text', 'image'], true)) {
                    continue;
                }

                Log::info('line.webhook.event', [
                    'type' => $messageType,
                    'message_id' => $message->getId(),
                ]);

                $customer = $upsertLineCustomerAction->execute($event, $userWebsiteId, $channelConfig->getAccessToken(), $config);
                $conversation = $upsertLineConversationAction->execute($customer);

                if ($messageType === 'image') {
                    $savedMessage = $createLineMessageAction->execute($conversation, $customer, $event, $config->id);
                    $createLineAttachmentMessageAction->execute(
                        $conversation,
                        $savedMessage,
                        $event,
                        $channelConfig->getAccessToken(),
                    );
                } else {
                    $createLineMessageAction->execute($conversation, $customer, $event, $config->id);
                }
            }
        }

        return $this->responseSuccess(new LineWebhookResultResource($events->count()));
    }
}
