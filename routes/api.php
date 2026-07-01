<?php

use App\Http\Controllers\PortalController;

Route::post('/clickup/webhook', [PortalController::class, 'handleClickUpWebhook']);
