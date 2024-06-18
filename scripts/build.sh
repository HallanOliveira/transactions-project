#!/bin/bash
# create enviorment files
cp ./.env.example ./.env;
cp ./.env.example ./.env.testing;
# .env
sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=1234/' .env
sed -i 's/^DB_USERNAME=.*/DB_USERNAME=default/' .env
sed -i 's/^DB_HOST.*/DB_HOST=db/' .env
# .env.testing
sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=1234/' .env.testing
sed -i 's/^DB_USERNAME=.*/DB_USERNAME=default/' .env.testing
sed -i 's/^DB_HOST=.*/DB_HOST=db-test/' .env.testing
sed -i 's/^DB_PORT=.*/DB_PORT=3306/' .env.testing

# configure, build and run docker
cp ./docker-compose-example.yml ./docker-compose.yml;
docker compose up -d --build;

# install dependencies and configure application
docker exec -u root -i transactions-app chmod -R 777 storage storage/logs;
docker exec -u root -i transactions-app chmod -R 777 ./docker-compose;
docker exec -u root -i transactions-app composer install;
docker exec -u root -i transactions-app php artisan key:generate;
docker exec -u root -i transactions-app php artisan optimize;
docker exec -u root -i transactions-app chmod -R 777 storage bootstrap/cache;
docker exec -u root -i transactions-app php artisan migrate;

# nginx config
echo "server {
    listen 80;
    server_name localhost;

    root /var/www/public;
    index index.php index.html index.htm;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param PATH_INFO \$fastcgi_path_info;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}" > ./docker-compose/nginx/conf.d/default.conf;
docker restart transactions-nginx;

# Friendly message
echo -e "\n---------------------------------------------------------------\n
| Done! The application is available on port 3001 on your PC. |\n
---------------------------------------------------------------";
