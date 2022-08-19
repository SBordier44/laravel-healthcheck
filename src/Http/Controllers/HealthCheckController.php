<?php

declare(strict_types=1);

namespace NBXDC\LaravelHealthCheck\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use NBXDC\LaravelHealthCheck\Model\Heartbeat;

class HealthCheckController extends BaseController
{
    public function readiness(): Response
    {
        return response('ok', 200);
    }

    public function livenessBackend(): Response
    {
        return response('ok', 200);
    }

    public function livenessDatabase(): Response
    {
        try {
            DB::connection()->getPdo();
            return response('ok', 200);
        } catch (\Exception $exception) {
            return response("No database connection", 503);
        }
    }

    public function livenessSchedule(): Response
    {
        try {
            Heartbeat::where('type', Heartbeat::TYPE_SCHEDULE)
                ->where('updated_at', '>', Date::now()->sub('5 minutes')->toDateTimeString())
                ->firstOrFail();
            return response('ok', 200);
        } catch (\Exception $exception) {
            return response(
                "Heartbeat for scheduled tasks has delayed 5 minutes or more. Please check that scheduled tasks are running.",
                503
            );
        }
    }

    public function livenessQueue(): Response
    {
        // Check queue worker
        try {
            Heartbeat::where('type', Heartbeat::TYPE_JOB)
                ->where('updated_at', '>', Date::now()->sub('5 minutes')->toDateTimeString())
                ->firstOrFail();
            return response('ok', 200);
        } catch (\Exception $exception) {
            return response(
                "Heartbeat for jobs has delayed 5 minutes or more. Please check that queue worker is running.",
                503
            );
        }
    }
}
