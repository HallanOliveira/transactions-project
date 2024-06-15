FROM php:8.1.27-fpm
RUN apt-get update -y && apt-get install -y openssl zip unzip git libonig-dev libxml2-dev
RUN apt-get clean
RUN docker-php-ext-install pdo pdo_mysql mbstring xml
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /var/www
COPY . /var/www

# Ajusta permissões da pasta de armazenamento e cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000