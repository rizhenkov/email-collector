# Email collector app

This app was created as test task.

Develop a microservice application that discovers email addresses of websites with the help of email discovery API. You will prompt a user for a list of URLs/domains and discover email addresses associated with those domains.

## Install

`git clone https://github.com/rizhenkov/email-collector && cd email-collector`

`composer i`

`cp .env.example .env`

`php artisan key:generate`

`php artisan migrate`

(optionally) `npm i`

## Deploy

Don't forget to install [Supervisor](https://laravel.com/docs/8.x/queues#installing-supervisor)

## Run Rabbitmq instance locally
`docker run -d --hostname my-rabbit --name some-rabbit -p 5672:5672 -p 8080:15672 rabbitmq:3-management`

