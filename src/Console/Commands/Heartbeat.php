<?php

declare(strict_types=1);

namespace NBXDC\LaravelHealthCheck\Console\Commands;

use Illuminate\Console\Command;
use NBXDC\LaravelHealthCheck\Model\Heartbeat as HeartbeatModel;

class Heartbeat extends Command
{
    protected string $signature = 'healthcheck:heartbeat';

    protected string $description = 'Update heartbeat timestamp';

    public function handle(): mixed
    {
        $heartbeat = HeartbeatModel::firstOrNew([
            'type' => HeartbeatModel::TYPE_SCHEDULE
        ]);
        $heartbeat->touch();
        $heartbeat->save();
    }
}
