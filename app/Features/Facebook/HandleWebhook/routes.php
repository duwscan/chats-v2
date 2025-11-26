<?php

use App\Features\Facebook\HandleWebhook\HandleFacebookWebhookController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->group(function (): void {
        Route::post('webhook/facebook', HandleFacebookWebhookController::class);
    });
