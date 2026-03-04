FROM php:8.3-fpm

# Install nginx, Composer, and PostgreSQL development libraries
RUN apt-get update && apt-get install -y \
    nginx \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy nginx configuration
COPY nginx.conf /etc/nginx/sites-available/default

# Copy SSL certificates
RUN mkdir -p /etc/nginx/ssl
COPY ssl/*.pem /etc/nginx/ssl/

# Copy application files
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set proper permissions for upload directories
RUN chown -R www-data:www-data /var/www/html/public/images && \
    chmod -R 775 /var/www/html/public/images

# Create a startup script
RUN echo '#!/bin/sh\n\
# Fix permissions on mounted volumes\n\
chown -R www-data:www-data /var/www/html/public/images\n\
chmod -R 775 /var/www/html/public/images\n\
php-fpm -D\n\
nginx -g "daemon off;"' > /start.sh && chmod +x /start.sh

EXPOSE 80 443

CMD ["/start.sh"]