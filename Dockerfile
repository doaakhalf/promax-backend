# -------------------------
# Stage 1: Build dependencies with Composer
# -------------------------
    FROM composer:2 AS vendor

    WORKDIR /app
    COPY composer.json composer.lock ./
    RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
    
    # -------------------------
    # Stage 2: PHP + Apache
    # -------------------------
    FROM ghcr.io/railwayapp-templates/php:8.1-apache
    
    # Set working directory
    WORKDIR /var/www/html
    
    # Install system dependencies & PHP extensions
    RUN apt-get update && apt-get install -y \
        libzip-dev unzip git curl \
        && docker-php-ext-install pdo pdo_mysql zip \
        && a2enmod rewrite
    
    # Copy existing app code
    COPY . .
    
    # Copy vendor from composer stage
    COPY --from=vendor /app/vendor ./vendor
    
    # Set Laravel permissions
    RUN chown -R www-data:www-data /var/www/html \
        && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
    
    # Expose port 8080 for Railway
    EXPOSE 8080
    
    # Start Apache
    CMD ["apache2-foreground"]
    