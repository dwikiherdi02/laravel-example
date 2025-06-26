FROM dunglas/frankenphp:1.5-php8.4.6

# Be sure to replace "your-domain-name.example.com" by your domain name
# ENV SERVER_NAME=your-domain-name.example.com
# If you want to disable HTTPS, use this value instead:
ENV SERVER_NAME=:80

# Install composer
COPY --from=composer:2.8.8 /usr/bin/composer /usr/bin/composer

# Install dependencies
RUN apt-get update && apt-get --no-install-recommends -y install \
    zip \
    unzip \
    autoconf \
    zlib1g-dev \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    supervisor

RUN install-php-extensions \
    pdo_mysql \
    gd \
    intl \
    zip \
    opcache \
    bcmath \
    exif \
    pcntl \
    fileinfo

# Enable PHP production settings
# RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY "./.docker/php.ini" "$PHP_INI_DIR/php.ini"

# Create supervisor log directory
RUN mkdir -p /var/log/supervisor

# Copy supervisor configuration
COPY "./.docker/supervisord.conf" "/etc/supervisor/conf.d/supervisord.conf"

# If you use Symfony or Laravel, you need to copy the whole project instead:
COPY . /app

# RUN mv /app/.env.example /app/.env
COPY "./.docker/.env" "/app/.env"

# RUN sed -i'' \
#     -e "s|^APP_ENV=.*|APP_ENV=${APP_ENV}|" \
#     -e "s|^APP_URL=.*|APP_URL=${APP_URL}|" \
#     -e "s|^APP_DEBUG=.*|APP_DEBUG=${APP_DEBUG}|" \
#     -e "s|^DB_HOST=.*|DB_HOST=${DB_HOST}|" \
#     -e "s|^DB_PORT=.*|DB_PORT=${DB_PORT}|" \
#     -e "s|^DB_DATABASE=.*|DB_DATABASE=${DB_DATABASE}|" \
#     -e "s|^DB_USERNAME=.*|DB_USERNAME=${DB_USERNAME}|" \
#     -e "s|^DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|" \
#     /app/.env

RUN mkdir -p /data/caddy/locks /config/caddy

RUN chown -R www-data:www-data /data /config

RUN chown -R www-data:www-data /app/public /app/storage /app/bootstrap/cache

RUN composer install --ignore-platform-reqs --no-dev -a

# RUN php artisan optimize

# RUN php artisan key:generate --force

RUN rm -rf /app/.docker /app/docker-compose.yml /app/.dockerignore

# Change the entrypoint so that supervisor manages both frankenphp and the queue worker
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
