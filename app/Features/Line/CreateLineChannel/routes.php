<?php

use App\Features\Line\CreateLineChannel\CreateLineChannelController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->prefix('api')
    ->group(function (): void {
        Route::post('line/channel', CreateLineChannelController::class);
    });
