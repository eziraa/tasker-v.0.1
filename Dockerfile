FROM php:8.2-apache

# Install SQLite extension
RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache modules mod_rewrite and mod_headers
RUN a2enmod rewrite headers

# Copy Apache configuration
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Copy ServerName config and enable it to suppress warning
COPY servername.conf /etc/apache2/conf-available/servername.conf
RUN a2enconf servername

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Create data directory and set permissions
RUN mkdir -p data && \
    chown -R www-data:www-data data && \
    chmod -R 755 data

# Set proper permissions for the web directory
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
