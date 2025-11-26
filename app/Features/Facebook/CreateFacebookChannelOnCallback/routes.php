<?php

use App\Features\Facebook\CreateFacebookChannelOnCallback\FacebookCallbackController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->group(function (): void {
        Route::get('facebook/oauth/callback', FacebookCallbackController::class);
    });
