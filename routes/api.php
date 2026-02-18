<?php

use App\Http\Controllers\Api\N8nController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your n8n integration.
| These routes are loaded by the RouteServiceProvider within a group
| which is assigned the "api" middleware group.
|
*/

Route::prefix('n8n')->middleware('n8n.token')->group(function () {
    Route::get('/client/{id}', [N8nController::class, 'getClient']);
    Route::post('/log', [N8nController::class, 'logInteraction']);
});
