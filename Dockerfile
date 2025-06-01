FROM dunglas/frankenphp:1.5-php8.4.6

ARG SERVER_NAME
ARG APP_ENV
ARG APP_URL
ARG APP_DEBUG
ARG DB_HOST
ARG DB_PORT
ARG DB_DATABASE
ARG DB_USERNAME
ARG DB_PASSWORD

# Be sure to replace "your-domain-name.example.com" by your domain name
# ENV SERVER_NAME=your-domain-name.example.com
# If you want to disable HTTPS, use this value instead:
ENV SERVER_NAME=${SERVER_NAME}

# Install composer
COPY --from=composer:2.8.8 /usr/bin/composer /usr/bin/composer

# Install dependencies
RUN apt-get update && apt-get --no-install-recommends -y install \
    zip \
    unzip \
    supervisor \
    g++ \
    autoconf \
    zlib1g-dev \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev

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

# RUN docker-php-ext-install pcntl && docker-php-ext-enable pcntl

# RUN docker-php-ext-install pdo_mysql && docker-php-ext-enable pdo_mysql

# RUN docker-php-ext-install bcmath && docker-php-ext-enable bcmath

# RUN docker-php-ext-install zip && docker-php-ext-enable zip

# RUN docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install -j$(nproc) gd

# Enable PHP production settings
# RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY "./.docker/php.ini" "$PHP_INI_DIR/php.ini"

WORKDIR /app

# Copy the PHP files of your project in the public directory
# COPY . /app/public
# If you use Symfony or Laravel, you need to copy the whole project instead:
COPY . /app

RUN mv /app/.env.example /app/.env

RUN sed -i'' \
    -e "s|^APP_ENV=.*|APP_ENV=${APP_ENV}|" \
    -e "s|^APP_URL=.*|APP_URL=${APP_URL}|" \
    -e "s|^APP_DEBUG=.*|APP_DEBUG=${APP_DEBUG}|" \
    -e "s|^DB_HOST=.*|DB_HOST=${DB_HOST}|" \
    -e "s|^DB_PORT=.*|DB_PORT=${DB_PORT}|" \
    -e "s|^DB_DATABASE=.*|DB_DATABASE=${DB_DATABASE}|" \
    -e "s|^DB_USERNAME=.*|DB_USERNAME=${DB_USERNAME}|" \
    -e "s|^DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|" \
    /app/.env

RUN chown -R www-data:www-data /app/public /app/storage /app/bootstrap/cache

RUN composer install --ignore-platform-reqs --no-dev -a

RUN php artisan key:generate --force

RUN rm -rf /app/.docker /app/Dockerfile /app/docker-compose.yml /app/.dockerignore
