FROM php:8.1-apache

# Enable mysqli
RUN docker-php-ext-install mysqli

# Copy your project files
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html
