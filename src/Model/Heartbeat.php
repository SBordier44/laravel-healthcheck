<?php

declare(strict_types=1);

namespace NBXDC\LaravelHealthCheck\Model;

use Illuminate\Database\Eloquent\Model;

class Heartbeat extends Model
{
    public const TYPE_SCHEDULE = 1;
    public const TYPE_JOB = 2;

    protected array $fillable = ['type'];
}
