# syntax=docker/dockerfile:1

FROM php:8.3-cli-bookworm

WORKDIR /app

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        curl \
        git \
        unzip \
        default-mysql-client \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
        libpng-dev \
        libxml2-dev \
        libzip-dev && \
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y --no-install-recommends nodejs && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install bcmath exif gd intl mbstring pcntl pdo_mysql zip && \
    rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock package.json package-lock.json ./

RUN composer install --no-interaction --prefer-dist --optimize-autoloader && \
    npm install

COPY . .

RUN npm run build && \
    mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache

EXPOSE 8000

CMD ["bash", "./run.sh"]
