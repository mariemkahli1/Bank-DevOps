# Use the official PHP image as the base image
FROM php:8.1-apache

# Set the working directory inside the container
WORKDIR /var/www/html

# Install required packages
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        unzip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer globally (latest stable version)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy the current directory contents into the container at /var/www/html
COPY src/ .

# Install dotenv via Composer in the src folder (latest stable version)
RUN composer require vlucas/phpdotenv

# Expose port 80 to the outside world
EXPOSE 80

# Set up environment variables from .env file
COPY src/.env /var/www/html/src/.env

# Ensure the container uses port 80 from the start
CMD ["apache2-foreground"]