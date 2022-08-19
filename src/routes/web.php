<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use NBXDC\LaravelHealthCheck\Http\Controllers\HealthCheckController;

Route::group(['prefix' => 'healthcheck'], function () {
    Route::get('/readiness', [HealthCheckController::class, 'readiness'])->name('readiness');
    Route::get('/liveness/backend', [HealthCheckController::class, 'livenessBackend'])->name('livenessBackend');
    Route::get('/liveness/database', [HealthCheckController::class, 'livenessDatabase'])->name('livenessDatabase');
    Route::get('/liveness/schedule', [HealthCheckController::class, 'livenessSchedule'])->name('livenessSchedule');
    Route::get('/liveness/queue', [HealthCheckController::class, 'livenessQueue'])->name('livenessQueue');
    Route::get('/liveness/components/{components}', [HealthCheckController::class, 'livenessComponents'])
        ->name('livenessComponents');
});
