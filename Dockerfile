
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libpq-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libwebp-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip mbstring exif

# Install Composer and Git safe directory config
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN git config --system --add safe.directory /var/www/html

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Install Composer dependencies
# Ensure we have a fresh vendor directory in the image
RUN rm -rf vendor && composer install --no-dev --optimize-autoloader

# Enable Apache modules
RUN a2enmod rewrite

# Configure Apache virtual host (optional, if needed)
# COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Expose port 80
EXPOSE 80
