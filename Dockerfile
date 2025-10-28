FROM php:8.2-apache

# Install required extensions for WordPress (MySQL support)
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
        netcat-traditional \
        mariadb-client-core \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli pdo pdo_mysql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Download WordPress core files
RUN apt-get update \
    && apt-get install -y --no-install-recommends curl \
    && curl -o wordpress.tar.gz https://wordpress.org/latest.tar.gz \
    && tar --strip-components=1 -xzf wordpress.tar.gz \
    && rm wordpress.tar.gz \
    && apt-get remove -y curl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copy project files (theme, plugins, wp-config generator, etc.)
COPY . .

# Fix permissions
RUN chmod +x start.sh \
    && chown -R www-data:www-data /var/www/html

# Enable mod_rewrite for WordPress pretty permalinks
RUN a2enmod rewrite

# Update Apache configuration to use DOCUMENT_ROOT
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html|g' /etc/apache2/sites-available/000-default.conf

# Set ServerName to suppress warnings
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 80

CMD ["./start.sh"]