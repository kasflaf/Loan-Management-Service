# Use the official PHP image with FPM (FastCGI Process Manager)
FROM php:8.1-fpm

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the application code from the local directory into the container
COPY ./Service /var/www/html

# Set the proper permissions for the copied files
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Grant necessary permissions for writable directory (CodeIgniter-specific)
RUN chown -R www-data:www-data /var/www/html/writable

# Install cron and other required dependencies
RUN apt-get update && apt-get install -y cron

# Set the working directory
WORKDIR /var/www/html

# Copy application code
COPY . /var/www/html

# Copy cron job file to /etc/cron.d/
COPY ./cronjob /etc/cron.d/loan-cron

# Set permissions for the cron job file
RUN chmod 0644 /etc/cron.d/loan-cron

# Apply the cron job
RUN crontab /etc/cron.d/loan-cron

# Ensure cron runs in the foreground
CMD ["cron", "-f"]