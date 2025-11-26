<?php

use App\Features\Widget\CreateAppAdapterChannel\CreateAppAdapterChannelController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->group(function (): void {
        Route::post('widget/channel', CreateAppAdapterChannelController::class);
    });
