<?php

use App\Features\Facebook\VerifyFacebookWebhook\VerifyFacebookWebhookController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->group(function (): void {
        Route::get('webhook/facebook', VerifyFacebookWebhookController::class);
    });
