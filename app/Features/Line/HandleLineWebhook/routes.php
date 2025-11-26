<?php

use App\Features\Line\HandleLineWebhook\HandleLineWebhookController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->group(function (): void {
        Route::post('webhook/line/{userWebsiteId}/{configId}', HandleLineWebhookController::class);
    });
