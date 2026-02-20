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
| ->middleware('n8n.token')
*/

Route::prefix('n8n')->group(function () {
    Route::get('/client/{id}', [N8nController::class, 'getClient']);
    Route::get('/clients/lookup', [N8nController::class, 'lookupClient']);
    Route::get('/clients', [N8nController::class, 'getClients']);
    Route::get('/logs', [N8nController::class, 'getLogs']);
    Route::get('/stats', [N8nController::class, 'getStats']);
    Route::post('/log', [N8nController::class, 'logInteraction']);
});
