FROM php:8.2-fpm

# Install required packages and extensions
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    zip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install pdo_mysql zip gd &&\
    docker-php-ext-install intl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Set working directory
WORKDIR /var/www

# Copy application code
COPY . /var/www

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini
RUN echo "upload_max_filesize=20M" > /usr/local/etc/php/conf.d/upload-max-filesize.ini
RUN echo "post_max_size=20M" > /usr/local/etc/php/conf.d/post-max-size.ini

