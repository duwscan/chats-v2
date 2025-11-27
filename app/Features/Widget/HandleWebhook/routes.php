<?php

use App\Features\Widget\HandleWebhook\HandleWidgetWebhookController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->group(function (): void {
        Route::post('widget/webhook/{userWebsiteId}/{configId}', HandleWidgetWebhookController::class);
    });
