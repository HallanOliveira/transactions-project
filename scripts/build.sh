#!/bin/bash
set -x;

# create enviorment files
cp ./.env.example ./.env;
cp ./.env.example ./.env.testing;
# .env
sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=123/' .env
sed -i 's/^DB_USERNAME=root/DB_USERNAME=default/' .env
sed -i 's/^DB_HOST=db/DB_HOST=db/' .env
# .env.testing
sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=123/' .env.testing
sed -i 's/^DB_USERNAME=root/DB_USERNAME=default/' .env.testing
sed -i 's/^DB_HOST=db/DB_HOST=db-test/' .env.testing
sed -i 's/^DB_PORT=3306/DB_PORT=33060/' .env.testing

# configure, build and run docker
cp ./docker-compose-example.yml ./docker-compose.yml;
docker compose up -d --build;

# nginx config
echo "server {
    listen 80;
    server_name localhost;

    root /var/www/public;
    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}" >> ./docker-compose/nginx/conf.d/default.conf;

# install dependencies and configure application
docker exec -i transactions-app chmod -R 777 storage storage/logs;
docker exec -i transactions-app composer install;
docker exec -i transactions-app php artisan key:generate;
docker exec -i transactions-app php artisan optimize;
docker exec -i transactions-app chmod -R 777 storage bootstrap/cache;
docker exec -i transactions-app php artisan migrate;
