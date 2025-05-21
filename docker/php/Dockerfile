FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# SET USER PERMISSIONS FOR COPIED APP.
RUN chown -R www-data:www-data /var/www/html

RUN git config --global --add safe.directory /var/www/

WORKDIR /var/www/html

