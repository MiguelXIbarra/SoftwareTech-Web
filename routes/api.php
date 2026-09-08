<?php

use App\Http\Controllers\Webhook\ClickUpWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/clickup/webhook', [ClickUpWebhookController::class, 'handle']);

