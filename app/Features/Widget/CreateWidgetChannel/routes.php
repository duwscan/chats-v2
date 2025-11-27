<?php

use App\Features\Widget\CreateWidgetChannel\CreateWidgetChannelController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->group(function (): void {
        Route::post('widget/channel', CreateWidgetChannelController::class);
    });
