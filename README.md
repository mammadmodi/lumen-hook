# LumenHook

This is a sample project developed by lumen framework to schedule web hooks through http apis.

## Application Parts

This application has three different roles:

1. Api
2. Worker
3. Scheduler

### Api

Api serves an http api in specified port.
you can see full documentation of available api's in [Swagger](https://petstore.swagger.io/?url=https://raw.githubusercontent.com/mammadmodi/lumen-hook/main/1.0.0-swagger.yaml) file.

You can simply setup an api server in port 8000 through following command:

`php -S localhost:8000 -t public`

### Worker

This application uses queue to run web hooks, so we need to set up a queue tu process web hook jobs: 

`php artisan queue:listen --queue=hooks`

### Scheduler

The last part is scheduler, you can schedule a hook by performing a POST request to ``http://localhost:8000/v1/hooks``
api, internally this stores a hook object with it's cron pattern in database, and after that we use [Laravel's task scheduler](https://laravel.com/docs/8.x/scheduling) 
to schedule hooks.

For this we need one cron job in our system to be ran in every minute:

`* * * * * su -c '/usr/local/bin/php /path/to/project/artisan schedule:run >> /var/log/schedule.log 2>&1'`


## Setup with docker compose

Simply you can use Docker to run all of the things, for this you should do only following command:

`make up`
