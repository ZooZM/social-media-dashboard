FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    autoconf \
    build-base \
    openssl-dev \
    php82-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git

RUN pecl install mongodb && docker-php-ext-enable mongodb

RUN docker-php-ext-install pdo pdo_mysql bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

CMD ["php-fpm"]