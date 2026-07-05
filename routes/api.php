<?php

use App\Http\Controllers\Api\DevicePairingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', function () {
        return response()->json([
            'success' => true,
            'message' => 'Photobooth API is running',
        ]);
    });

    Route::post('/devices/pair', [DevicePairingController::class, 'pair']);
});
