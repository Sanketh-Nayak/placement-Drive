FROM php:8.1-apache

# Install MySQL extension for PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy application code to web root
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose default Apache port
EXPOSE 80
