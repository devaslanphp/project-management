# syntax=docker/dockerfile:1

#Deriving the latest base image
FROM node:latest

#Labels as key value pair
LABEL Maintainer="Jacco van Ekris"

# Any working directory can be chosen as per choice like '/' or '/home' etc
WORKDIR /app

RUN apt-get update -y

RUN apt-get install -y software-properties-common gnupg2

RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/sury-php.list

RUN wget -qO - https://packages.sury.org/php/apt.gpg | apt-key add -

RUN apt-get update -y

RUN apt-get install -y php8.1 php8.1-curl php8.1-xml php8.1-zip php8.1-gd php8.1-mbstring php8.1-mysql

RUN apt-get update -y

RUN service apache2 restart

RUN apt-get install -y composer

COPY . .

RUN composer update

RUN composer install

RUN npm install

RUN mkdir /app/data

RUN php artisan key:generate

CMD [ "bash", "./run.sh"]