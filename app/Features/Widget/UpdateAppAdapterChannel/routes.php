<?php

use App\Features\Widget\UpdateAppAdapterChannel\UpdateAppAdapterChannelController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->group(function (): void {
        Route::put('widget/channel/{configId}', UpdateAppAdapterChannelController::class);
    });
