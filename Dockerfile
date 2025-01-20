# Use an official PHP runtime as a parent image
FROM php:8.1-fpm

# Install system dependencies and PHP extensions required by Laravel
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev libzip-dev git unzip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd zip pdo pdo_mysql

# Set the working directory
WORKDIR /var/www

# Copy composer.lock and composer.json to the container
COPY composer.json composer.lock /var/www/

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy the rest of the application code
COPY . /var/www/

# Expose the port that the app will run on
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
