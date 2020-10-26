FROM php:7.4-fpm

RUN apt-get update \
    && pecl install redis \
    && docker-php-ext-install bcmath pdo_mysql \
    && docker-php-ext-enable redis
