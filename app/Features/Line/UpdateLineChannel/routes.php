<?php

use App\Features\Line\UpdateLineChannel\UpdateLineChannelController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->group(function (): void {
        Route::put('line/channel/{configId}', UpdateLineChannelController::class);
    });
