<?php

declare(strict_types=1);

namespace NBXDC\LaravelHealthCheck\Console\Commands;

use Illuminate\Console\Command;
use NBXDC\LaravelHealthCheck\Model\Heartbeat as HeartbeatModel;

class Heartbeat extends Command
{
    protected $signature = 'healthcheck:heartbeat';

    protected $description = 'Update heartbeat timestamp';

    public function handle(): void
    {
        $heartbeat = HeartbeatModel::firstOrNew([
            'type' => HeartbeatModel::TYPE_SCHEDULE
        ]);
        $heartbeat->touch();
        $heartbeat->save();
    }
}
