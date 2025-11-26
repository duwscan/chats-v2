<?php

use App\Features\Facebook\GenerateFacebookConnectUrl\GenerateFacebookConnectUrlController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->group(function (): void {
        Route::get('facebook/oauth/{userWebsiteId}/url', GenerateFacebookConnectUrlController::class);
    });
