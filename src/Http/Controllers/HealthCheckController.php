<?php

declare(strict_types=1);

namespace NBXDC\LaravelHealthCheck\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use NBXDC\LaravelHealthCheck\Model\Heartbeat;

class HealthCheckController extends BaseController
{
    private bool $stacked = false;

    public function readiness(): Response|bool
    {
        return $this->stacked ? true : \response('ok', 200);
    }

    public function livenessBackend(): Response|bool
    {
        return $this->stacked ? true : \response('ok', 200);
    }

    public function livenessDatabase(): Response|bool
    {
        try {
            DB::connection()->getPdo();
            return $this->stacked ? true : \response('ok', 200);
        } catch (\Exception $exception) {
            return $this->stacked ? false : \response("No database connection", 503);
        }
    }

    public function livenessSchedule(): Response|bool
    {
        try {
            Heartbeat::where('type', Heartbeat::TYPE_SCHEDULE)
                ->where('updated_at', '>', Date::now()->sub('5 minutes')->toDateTimeString())
                ->firstOrFail();
            return $this->stacked ? true : \response('ok', 200);
        } catch (\Exception $exception) {
            return $this->stacked ? false : \response(
                "Heartbeat for scheduled tasks has delayed 5 minutes or more. Please check that scheduled tasks are running.",
                503
            );
        }
    }

    public function livenessQueue(): Response|bool
    {
        try {
            Heartbeat::where('type', Heartbeat::TYPE_JOB)
                ->where('updated_at', '>', Date::now()->sub('5 minutes')->toDateTimeString())
                ->firstOrFail();
            return $this->stacked ? true : \response('ok', 200);
        } catch (\Exception $exception) {
            return $this->stacked ? false : \response(
                "Heartbeat for jobs has delayed 5 minutes or more. Please check that queue worker is running.",
                503
            );
        }
    }

    public function livenessComponents(string $components = ''): Response
    {
        $this->stacked = true;
        $extractedComponents = explode(',', trim($components));

        $checkedComponents = [];
        foreach ($extractedComponents as $component) {
            $method = 'liveness' . ucfirst(trim($component));
            if (method_exists($this, $method)) {
                $checkedComponents[] = $component;
                if (!$this->$method()) {
                    return \response("The component $component is not available", 503);
                }
            }
        }

        if (empty($checkedComponents)) {
            \response(
                "None of the specified components ($components) could be checked because they do not exists. 
                Please correct your request.",
                400
            );
        }

        return \response(
            'All specified components (' . implode(', ', $checkedComponents) . ') are up and running !',
            200
        );
    }
}
