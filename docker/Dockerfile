FROM php:7.4-apache

RUN apt-get update && apt-get install -y \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libzip-dev \
        libicu-dev \
        libcurl4-openssl-dev \
        libxml2-dev \
        unzip \
        git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" gd mysqli pdo_mysql zip intl curl mbstring bcmath \
    && a2enmod rewrite \
    && { \
        echo '<Directory /var/www/html>'; \
        echo '    AllowOverride All'; \
        echo '</Directory>'; \
       } > /etc/apache2/conf-available/allow-override.conf \
    && a2enconf allow-override \
    && rm -rf /var/lib/apt/lists/*

COPY php.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html
