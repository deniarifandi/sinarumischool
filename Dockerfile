FROM php:8.3-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y libicu-dev \
    && docker-php-ext-install mysqli intl \
    && a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html

# Copy project files into the container
COPY . /var/www/html