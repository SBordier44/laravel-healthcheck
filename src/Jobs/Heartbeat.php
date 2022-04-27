<?php

declare(strict_types=1);

namespace NBXDC\LaravelHealthCheck\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use NBXDC\LaravelHealthCheck\Model\Heartbeat as HeartbeatModel;

class Heartbeat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function handle(): mixed
    {
        $heartbeat = HeartbeatModel::firstOrNew([
            'type' => HeartbeatModel::TYPE_JOB
        ]);
        $heartbeat->touch();
        $heartbeat->save();
    }
}
