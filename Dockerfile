# Shared build between dev and prod
FROM php:8.3-apache AS build
# Set the apache server name
RUN echo "ServerName uni-project" >> /etc/apache2/apache2.conf
# Install dependencies
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    zip
# Enable mod_rewrite
RUN a2enmod rewrite
# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip
# Set document root env variable
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
# Set the working directory
WORKDIR /var/www/html
# Copy the application code
COPY --chown=www:www . .
# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Set permissions
RUN chown -R www-data:www-data ./storage ./bootstrap/cache

# Run as development
FROM build AS development
# Install project dependencies
RUN composer install

# Run as prod
FROM build AS production
# Install project dependencies
RUN composer install --optimize-autoloader --no-dev
# Optimise Laravel framework
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
