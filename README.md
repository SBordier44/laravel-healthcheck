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
   $schedule->job(Heartbeat::class)->everyMinute(); // For monitor job queue
   ```

## Using the health checks
Vous pouvez configurer votre monitoring pour envoyer un ping aux URLs des sondes de santé afin d'être alerté en cas de problème.\
Dans Kubernetes, vous pouvez également utiliser les sondes pour les vérifications de l'état des conteneurs avec les LivenessProbe & ReadinessProbe.\
https://kubernetes.io/docs/tasks/configure-pod-container/configure-liveness-readiness-startup-probes/

### Liveness Probes
There are dedicated liveness probes for different services.\
They will response with http 200 status code if the service is up and functions without problems.

Https status 503 is returned if the service is not available.
- Backend service: `{APP_URL}/healthcheck/liveness/backend`
- Database service: `{APP_URL}/healthcheck/liveness/database`
- Schedule service: `{APP_URL}/healthcheck/liveness/schedule`
- Queue service: `{APP_URL}/healthcheck/liveness/queue`

### Readiness Probe
Readiness probe is identical for all services: `{APP_URL}/healthcheck/readiness`\
It will response with http 200 status code if the service is ready to take http requests.
