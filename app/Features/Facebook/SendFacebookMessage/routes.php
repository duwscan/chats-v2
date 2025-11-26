<?php

use App\Features\Facebook\SendFacebookMessage\SendFacebookMessageController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->prefix('api')
    ->group(function (): void {
        Route::post('facebook/message/send', SendFacebookMessageController::class);
    });
