<?php

namespace App\Features\Widget\HandleWebhook;

use App\Exceptions\CustomException;
use App\Features\Widget\HandleWebhook\Actions\CreateWidgetMessageAction;
use App\Features\Widget\HandleWebhook\Actions\UpsertWidgetConversationAction;
use App\Features\Widget\HandleWebhook\Actions\UpsertWidgetCustomerAction;
use App\Http\Controllers\ApiResponseTrait;
use App\Models\ChannelWebhookConfig;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

#[Group('Widget Webhooks')]
class HandleWidgetWebhookController
{
    use ApiResponseTrait;

    /**
     * Handle incoming Widget webhook events.
     *
     * @response WidgetWebhookResultResource
     *
     * @status 200
     */
    public function __invoke(
        HandleWidgetWebhookRequest $request,
        UpsertWidgetCustomerAction $upsertWidgetCustomerAction,
        UpsertWidgetConversationAction $upsertWidgetConversationAction,
        CreateWidgetMessageAction $createWidgetMessageAction,
    ): JsonResponse {
        $userWebsiteId = $request->routeUserWebsiteId();
        $configId = $request->routeConfigId();

        $config = ChannelWebhookConfig::query()
            ->where('id', $configId)
            ->where('user_website_id', $userWebsiteId)
            ->where('channel', 'widget')
            ->first();

        if (! $config) {
            throw new CustomException('Widget config not found.', 404);
        }

        $validated = $request->validated();

        Log::info('widget.webhook.event', [
            'user_website_id' => $userWebsiteId,
            'config_id' => $configId,
            'visitor_id' => $validated['visitor_id'],
            'message_id' => $validated['message_id'] ?? null,
        ]);

        $customer = $upsertWidgetCustomerAction->execute(
            $validated['visitor_id'],
            $userWebsiteId,
            $config,
        );

        $conversation = $upsertWidgetConversationAction->execute($customer);

        $createWidgetMessageAction->execute(
            $conversation,
            $customer,
            $validated['message'],
            $validated['message_id'] ?? null,
            $validated['metadata'] ?? [],
        );

        return $this->responseSuccess(new WidgetWebhookResultResource(1));
    }
}
