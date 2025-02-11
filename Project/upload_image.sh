#!/bin/bash

# Stop execution if a step fails
set -e

# Replace with your group's image name
IMAGE_NAME=gitlab.up.pt:5050/lbaw/lbaw2425/lbaw2432

# Ensure that dependencies are available
composer install
composer require pusher/pusher-php-server
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan clear-compiled
php artisan optimize

php artisan storage:unlink
php artisan storage:link

npm install
npm run build

# docker buildx build --push --platform linux/amd64 -t $IMAGE_NAME .
docker build -t $IMAGE_NAME .
docker push $IMAGE_NAME