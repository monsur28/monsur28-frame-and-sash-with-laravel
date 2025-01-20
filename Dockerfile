# Use the official PHP image with FPM
FROM php:8.1-fpm

# Install system dependencies and PHP extensions required for Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    git \
    unzip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd zip pdo pdo_mysql mbstring

# Set working directory
WORKDIR /var/www

# Copy composer files
COPY composer.json composer.lock /var/www/

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependencies
RUN chown -R www-data:www-data /var/www && \
    composer install --no-dev --optimize-autoloader

# Copy the rest of the application code
COPY . /var/www/

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
