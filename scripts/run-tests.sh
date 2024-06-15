#!/bin/bash
docker exec -i transactions-app chmod -R 777 storage storage/logs
docker exec -i transactions-app php artisan optimize --env=testing
docker exec -i transactions-app php artisan key:generate --env=testing
docker exec -i transactions-app chmod -R 777 storage bootstrap/cache
docker exec -i transactions-app composer dump
docker exec -i transactions-app php artisan migrate:fresh --seed --force --env=testing
if [ -z "$1" ]
then
    docker exec -i transactions-app php artisan test --env=testing
else
    docker exec -i transactions-app php artisan test --env=testing --filter=$1
fi
