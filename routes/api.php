<?php

use App\Http\Controllers\Api\DevicePairingController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\PhotoUploadController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', function () {
        return response()->json([
            'success' => true,
            'message' => 'Photobooth API is running',
        ]);
    });

    Route::post('/devices/pair', [DevicePairingController::class, 'pair']);
    Route::post('/devices/heartbeat', [DeviceController::class, 'heartbeat']);
    Route::get('/devices/event-settings', [DeviceController::class, 'eventSettings']);
    Route::post('/events/{event}/photos', [PhotoUploadController::class, 'store']);
});
