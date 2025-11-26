<?php

namespace App\Features\Facebook\SendFacebookMessage;

use App\Exceptions\CustomException;
use App\Features\Facebook\SendFacebookMessage\Text\FacebookTextMessage;
use App\Features\Facebook\SendFacebookMessage\Text\SendFacebookTextMessageAction;
use App\Http\Controllers\ApiResponseTrait;
use App\Models\CustomerModel;
use Illuminate\Http\JsonResponse;

class SendFacebookMessageController
{
    use ApiResponseTrait;

    /**
     * Send a Facebook message to a customer.
     *
     * @response SendFacebookMessageResultResource
     *
     * @status 200
     */
    public function __invoke(
        SendFacebookMessageRequest $request,
        SendFacebookTextMessageAction $textAction,
    ): JsonResponse {
        $data = $request->validated();

        $customer = CustomerModel::query()->find($data['customer_id']);

        if (! $customer) {
            throw new CustomException('Customer not found.', 404);
        }

        if ($customer->channel !== 'facebook') {
            throw new CustomException('Customer is not a Facebook customer.', 400);
        }

        $type = $data['type'] ?? 'text';

        if ($type === 'text') {
            // Get config_id and user_website_id from customer
            $messageData = array_merge($data, [
                'config_id' => $customer->channel_webhook_config_id,
                'user_website_id' => $customer->user_website_id,
            ]);

            $message = FacebookTextMessage::fromArray($messageData);

            $textAction->execute($customer, $message);
        } else {
            throw new CustomException('Unsupported Facebook message type: '.$type, 400);
        }

        return $this->responseSuccess(new SendFacebookMessageResultResource(
            message: 'Facebook message sent successfully',
            customerId: $customer->id,
            type: $type,
        ));
    }
}
