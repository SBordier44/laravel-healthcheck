<?php

declare(strict_types=1);

namespace NBXDC\LaravelHealthCheck;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use NBXDC\LaravelHealthCheck\Console\Commands\Heartbeat as HeartbeatCommand;
use NBXDC\LaravelHealthCheck\Http\Controllers\HealthCheckController;
use NBXDC\LaravelHealthCheck\Jobs\Heartbeat as HeartbeatJob;

class HealthCheckServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->make(HealthCheckController::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        if (!$this->app->runningInConsole() || $this->app->environment() === 'testing') {
            return;
        }

        $this->commands(HeartbeatCommand::class);

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->job(new HeartbeatJob())->everyMinute();
            $schedule->command('healthcheck:heartbeat')->everyMinute();
        });
    }
}
