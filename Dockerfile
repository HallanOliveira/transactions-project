FROM php:8.1.27-fpm
RUN apt-get update -y && apt-get install -y openssl zip unzip git libonig-dev libxml2-dev
RUN apt-get clean
RUN docker-php-ext-install pdo pdo_mysql mbstring xml

# Instala o Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Configura o Xdebug para cobertura de código (ajuste conforme necessário)
RUN echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /var/www
COPY . /var/www

# Ajusta permissões da pasta de armazenamento e cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
