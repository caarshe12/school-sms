# Use PHP 8.2 with FPM
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files first for cache efficiency
COPY composer.json composer.lock ./

# Install dependencies (Optimize for production)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy application files
COPY . .

# Install frontend dependencies and build assets
RUN npm install && npm run build

# Set permissions
RUN mkdir -p /var/www/storage/framework/cache \
    && mkdir -p /var/www/storage/framework/sessions \
    && mkdir -p /var/www/storage/framework/views \
    && mkdir -p /var/www/storage/logs \
    && chown -R www-data:www-data /var/www \
    && chmod -R 777 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Generate key if needed (or rely on APP_KEY env var)
# RUN php artisan key:generate

# Make start script executable
RUN chmod +x docker/entrypoint.sh

# Expose port (Render sets PORT env variable, but we default to 8000 for local)
EXPOSE 8000

# Start command using entrypoint script
ENTRYPOINT ["docker/entrypoint.sh"]
