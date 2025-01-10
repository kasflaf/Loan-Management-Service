# Use the official PHP image with FPM (FastCGI Process Manager)
FROM php:8.1-fpm

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the application code from the local directory into the container
COPY ./Service /var/www/html

# Set the proper permissions for the copied files
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html