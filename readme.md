## Table of contents
* [General info](#general-info)
* [Challenge Description](#challenge-description)
* [Technologies](#technologies)
* [Setup](#setup)

## General info
This API REST is made by Patricio Quezada Habert for the Giant Monkey Robot PHP Programming Challenge

Challenge description:
Implement a priority queue web-api server (REST API), that can be used to add and update “jobs” on a queue. Each job consists of a job id, submitter’s id, processor’s id (if its being processed) and a command to execute.

Job processors must be able to pick the current non-completed job with the highest priority. No two job processors should pick the same job.

Job submitters may also be able to check the status of a job using an id that was returned
to them when they added the job to the queue. One client may be able to submit more than one job. One job processor may only process one job at a time.

The server must save the jobs in a file or database and allow for hundreds or even thousands of job submitters and job processors to add and update jobs on the queue simultaneously.

Provide a way to get current average processing time. Consider using a caching mechanism to optimize access to the queue data. Feel free to use open source tools and software. 


What to submit:

●	Source code
●	Instructions for installing / testing (we welcome scripts)
●	Data for testing
●	Rest API documentation


## Challenge Description
	
## Technologies
Challenge is created with:
* Laravel: 7.30.4 | Base Framework
* Laravel Passport: 8.0 | For OAUTH2 based authentication
* Predis: 1.1 | For Jobs and Queue processing
* Laravel Horizon: 4.3.5 | For Jobs and Queue monitoring
* L5-Swagger: 8.0 | For API Rest Documentation with OAS3 Standard
	
## Setup
To run this project, install it:

```
$ git clone http://foo.bar
$ composer install
$ cp .env.example .env
$ php artisan key:generate
$ php artisan migrate --seed
$ php artisan passport:install

```