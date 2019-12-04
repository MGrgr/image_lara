#!/bin/bash

composer install

cp .env.example .env

php artisan key:generate
php artisan migrate
php artisan storage:link

npm install
npm run dev