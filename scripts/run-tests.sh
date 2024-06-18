#!/bin/bash
docker exec -u root -i transactions-app chmod -R 777 storage storage/logs
docker exec -u root -i transactions-app php artisan optimize --env=testing
docker exec -u root -i transactions-app php artisan key:generate --env=testing
docker exec -u root -i transactions-app chmod -R 777 storage bootstrap/cache
docker exec -u root -i transactions-app composer dump
docker exec -u root -i transactions-app php artisan migrate:fresh --seed --force --env=testing
if [ -z "$1" ]
then
    docker exec -u root -i transactions-app php artisan test --env=testing
else
    docker exec -u root -i transactions-app php artisan test --env=testing --filter=$1
fi
