# Email collector app

This app was created as test task.

## Install

`composer i`

`cp .env.example .env`

(optionally) `npm i`

## Deploy

Don't forget to install [Supervisor](https://laravel.com/docs/8.x/queues#installing-supervisor)

## Run Rabbitmq instance locally
`docker run -d --hostname my-rabbit --name some-rabbit -p 5672:5672 -p 8080:15672 rabbitmq:3-management`

