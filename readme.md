# Giant Monkey Robot PHP Challenge
![alt text](https://github.com/anguswild/gmr-php-challenge/blob/main/public/logo.jpg "Logo")

## Table of contents
* [General info](#general-info)
* [Challenge Description](#challenge-description)
* [APP Technologies](#app-technologies)
* [Server Technologies](#server-technologies)
* [Setup](#setup)
* [Additional Information](#additional-information)

## General info
This API REST is made by Patricio Quezada Habert for the Giant Monkey Robot PHP Programming Challenge

## Challenge Description
Implement a priority queue web-api server (REST API), that can be used to add and update “jobs” on a queue. Each job consists of a job id, submitter’s id, processor’s id (if its being processed) and a command to execute.

Job processors must be able to pick the current non-completed job with the highest priority. No two job processors should pick the same job.

Job submitters may also be able to check the status of a job using an id that was returned
to them when they added the job to the queue. One client may be able to submit more than one job. One job processor may only process one job at a time.

The server must save the jobs in a file or database and allow for hundreds or even thousands of job submitters and job processors to add and update jobs on the queue simultaneously.

Provide a way to get current average processing time. Consider using a caching mechanism to optimize access to the queue data. Feel free to use open source tools and software. 


What to submit:

* Source code
* Instructions for installing / testing (we welcome scripts)
* Data for testing
* Rest API documentation
	
## APP Technologies
The Challenge has been created with:
* Laravel: 7.30.4 | Base Framework
* Laravel Passport: 8.0 | For OAUTH2 based authentication
* Predis: 1.1 | For Jobs and Queue processing 
* Laravel Horizon: 4.3.5 | For Jobs and Queue monitoring
* L5-Swagger: 8.0 | For API Rest Documentation with OAS3 Standard
* Spatie Laravel-permission | For Associating users with roles and permissions

## Server Technologies
The Server that holds the Challenge is using:
* Docker
* PHP-FPM (with Zeng Engine OPCACHE)
* Nginx (Linux Alpine Version for Docker)
* Mysql
* Redis
	
## Setup
To run this project, you must install it in a docker environment with the following commands:

```
git clone https://github.com/anguswild/gmr-php-challenge.git
docker-compose up -d
docker exec app composer install
cp .env.example .env
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
docker-compose exec app php artisan passport:install
```

The following credentials contain all permissions to test the system:
```
admin:8dB8ZFfWcmB3ZkqL
```


Additionally we have the following commands:

For Redis Job Queue Processing
```
docker-compose exec app php artisan queue:work
```

For PHP UNIT Testing ( Only for auth, sorry :( )
```
docker-compose exec app php artisan test
```

## Additional Information

When the application is installed, it is important to note that there are two FRONTEND routes
* %APP_URL%/ (Index of the application that shows all the routes and documentation of the api)
* %APP_URL%/horizon/dashboard (For Jobs and Queue real-time monitoring)