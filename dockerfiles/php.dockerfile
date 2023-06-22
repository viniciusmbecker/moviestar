# Use the official PHP 8.1 image as the base
FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    g++ \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zip \
    zlib1g-dev \
    unzip \
    libonig-dev \
    curl \
    nano

# Clear cache
RUN apt clean && rm -rf /var/lib/apt/lists/*

# Install extensions for php
RUN docker-php-ext-install intl opcache pdo_mysql mbstring zip exif pcntl    

ENV APP_HOME /var/www/html

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

RUN sed -i -e "s/html/html/" /etc/apache2/sites-enabled/000-default.conf

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy existing application directory contents to the working directory
COPY . $APP_HOME

RUN chown -R www-data:www-data $APP_HOME