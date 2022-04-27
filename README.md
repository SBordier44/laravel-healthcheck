# Laravel Healh Check

## Requirements
- PHP ^8.1
- Laravel ^9

## Installation
Run `composer require nuboxdevcom/laravel-healthcheck` in the app root

## Using the health checks
You can set your monitoring system to ping the liveness and readiness URLs to get alerted if there are any problems.\
In Kubernetes and OpenShift you can use the probes also for container health checks.

### Liveness Probes
There are dedicated liveness probes for different services.\
They will response with http 200 status code if the service is up and functions without problems.\
Https status 503 is returned if the service is not available.
- Backend service: `{APP_URL}/health-check/liveness/backend`
- Database service: `{APP_URL}/health-check/liveness/database`
- Schedule service: `{APP_URL}/health-check/liveness/schedule`
- Queue service: `{APP_URL}/health-check/liveness/queue`

### Readiness Probe
Readiness probe is identical for all services: `{APP_URL}/health-check/readiness`\
It will response with http 200 status code if the service is ready to take http requests.
