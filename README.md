# Laravel HealhCheck

## Requirements

- PHP ^8.1
- Laravel ^9

## Installation

1) `composer require nuboxdevcom/laravel-healthcheck`
2) `php artisan migrate`
3) Add these lines in src/Console/Kernel.php if your uses scheduler and job queue:
   ```php
   $schedule->command('healthcheck:heartbeat')->everyMinute(); // For monitor scheduler
   $schedule->job(Heartbeat::class)->everyMinute(); // For monitor job queue (redis/horizon/...)
   ```

## Using the health checks

You can configure your monitoring to send a ping to the URLs of the health probes in order to be alerted in the event of
a problem.\
In Kubernetes, you can also use probes for container health checks with the LivenessProbe & ReadinessProbe.\
https://kubernetes.io/docs/tasks/configure-pod-container/configure-liveness-readiness-startup-probes/

### Liveness Probes

There are dedicated liveness probes for different services.\
They will response with http 200 status code if the service is up and running without problems (all services available).

Https status 503 is returned if the service is not available.

- Backend service: `{APP_URL}/healthcheck/liveness/backend`
- Database service: `{APP_URL}/healthcheck/liveness/database`
- Schedule service: `{APP_URL}/healthcheck/liveness/schedule`
- Queue service: `{APP_URL}/healthcheck/liveness/queue`
- Custom components checks: `{APP_URL}/healthcheck/liveness/components/{comp1,comp2,...}`
  > For custom checks, availables components is `queue`|`schedule`|`database`|`backend` with comma separated \
  > Eg: `{APP_URL}/healthcheck/liveness/components/{queue,schedule,database,backend}`

### Readiness Probe

Readiness probe is identical for all services: `{APP_URL}/healthcheck/readiness`\
It will response with http 200 status code if the service is ready to take http requests.
