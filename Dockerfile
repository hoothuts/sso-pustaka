FROM php:7.4-apache

RUN rm -rf /etc/apt/sources.list.d/* \
 && echo "deb http://archive.debian.org/debian bullseye main contrib non-free" > /etc/apt/sources.list \
 && echo "deb http://archive.debian.org/debian-security bullseye-security main contrib non-free" >> /etc/apt/sources.list \
 && echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99no-check-valid-until \
 && apt-get update \
 && apt-get install -y --allow-downgrades \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libzip-dev \
        libicu-dev \
        libcurl4-openssl-dev \
        libxml2-dev \
        libonig-dev \
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
