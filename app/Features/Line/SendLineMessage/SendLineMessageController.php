<?php

namespace App\Features\Line\SendLineMessage;

use App\Exceptions\CustomException;
use App\Features\Line\SendLineMessage\Text\SendLineTextMessageAction;
use App\Features\Line\SendLineMessage\Text\SendLineTextMessageData;
use App\Http\Controllers\ApiResponseTrait;
use App\Models\CustomerModel;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;

#[Group('LINE Messaging')]
class SendLineMessageController
{
    use ApiResponseTrait;

    /**
     * Send a LINE message to a customer.
     *
     * @response SendLineMessageResultResource
     *
     * @status 200
     */
    public function __invoke(
        SendLineMessageRequest $request,
        SendLineTextMessageAction $sendLineTextMessageAction,
    ): JsonResponse {
        $validated = $request->validated();

        $customer = CustomerModel::query()->find($validated['customer_id']);

        if (! $customer) {
            throw new CustomException('Customer not found.', 404);
        }

        if ($customer->channel !== 'line') {
            throw new CustomException('Customer is not a LINE customer.', 400);
        }

        $type = $validated['type'];

        if ($type === 'text') {
            $data = new SendLineTextMessageData(
                text: $validated['text'],
                replyToken: $validated['reply_token'] ?? null,
                conversationId: $validated['conversation_id'] ?? null,
                agentId: $validated['agent_id'] ?? null,
                replyToId: $validated['reply_to_id'] ?? null,
            );

            $sendLineTextMessageAction->execute($customer, $data);
        } else {
            throw new CustomException('Unsupported LINE message type.', 400);
        }

        return $this->responseSuccess(new SendLineMessageResultResource(
            message: 'LINE message sent successfully',
            customerId: $customer->id,
            type: $type,
        ));
    }
}
