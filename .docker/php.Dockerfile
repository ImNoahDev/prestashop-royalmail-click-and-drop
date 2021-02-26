# syntax=docker/dockerfile:experimental
FROM composer:2 AS composer

FROM php:7.2-apache

COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV COMPOSER_HOME=/.composer
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update \
    && apt-get install -y \
        libzip-dev \
        libpng-dev \
        libxml2-dev \
        libicu-dev \
        locales \
        locales-all \
        libfreetype6-dev \
	    libjpeg62-turbo-dev \
	    libpng-dev \
        wget
RUN docker-php-ext-configure gd --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
        zip \
        gd \
        pdo_mysql \
        intl \
	&& docker-php-ext-enable gd

ARG development=true
ARG phpmemlimit=1G

RUN if [ $development = "true" ]; \
    then \
    echo "Setting PHP.ini config" \
    && cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini \
    && sed -i "s/memory_limit = .*/memory_limit = ${phpmemlimit}/" /usr/local/etc/php/php.ini; \
    fi

RUN a2enmod rewrite

WORKDIR /var/www/html