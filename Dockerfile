# Use Railway PHP image instead of DockerHub
FROM ghcr.io/railwayapp-templates/php:8.1-apache

# Set working directory
WORKDIR /app

# Copy files
COPY . .

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git && \
    docker-php-ext-install pdo pdo_mysql zip

# Expose Apache port
EXPOSE 8080

# Railway expects Apache to run on port 8080
CMD ["apache2-foreground"]
