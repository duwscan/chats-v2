<?php

use App\Features\Line\SendLineMessage\SendLineMessageController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->group(function (): void {
        Route::post('line/message/send', SendLineMessageController::class);
    });
