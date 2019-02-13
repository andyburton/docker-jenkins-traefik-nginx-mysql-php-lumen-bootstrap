FROM php:7.2.6-fpm-alpine3.7

# Work from /app dir
WORKDIR /app

# PHP extensions
RUN docker-php-ext-install -j$(nproc) \
      json \
      pdo_mysql

# Scripts
COPY scripts /scripts

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV PATH ~/.composer/vendor/bin:$PATH

# Lumen permissions
COPY --chown=www-data:www-data src/ /app
RUN chmod -R ug+rwx /app/storage

# Install composer packages
RUN composer install -n -o

# Run PHP FPM
CMD "php-fpm"