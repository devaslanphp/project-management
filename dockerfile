# syntax=docker/dockerfile:1

#Deriving the latest base image
FROM node:16.17.0-bullseye-slim

# Any working directory can be chosen as per choice like '/' or '/home' etc
WORKDIR /app

COPY .env.example .env

COPY . .

RUN apt-get update -y && \
    apt-get install -y --no-install-recommends software-properties-common gnupg2 wget && \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/sury-php.list && \
    wget -qO - https://packages.sury.org/php/apt.gpg | apt-key add - && \
    apt-get update -y && \
    apt-get install -y --no-install-recommends php8.1 php8.1-curl php8.1-xml php8.1-zip php8.1-gd php8.1-mbstring php8.1-mysql && \
    apt-get update -y && \
    apt-get install -y composer && \
    composer update && \
    composer install && \
    npm install && \
    php artisan key:generate && \
    rm -rf /var/lib/apt/lists/*

CMD [ "bash", "./run.sh"]
