<?php

use App\Features\Facebook\SendFacebookMessage\SendFacebookMessageController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->group(function (): void {
        Route::post('facebook/message/send', SendFacebookMessageController::class);
    });
