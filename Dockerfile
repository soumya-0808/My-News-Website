# Use the official PHP image with Apache (latest)
FROM php:8.2-apache

# Enable the Apache mod_rewrite module
RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html

# Copy your PHP website files into the container
COPY . ./

# Expose port 80
EXPOSE 4444

# No CMD needed as the default command of Apache is sufficient
