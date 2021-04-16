FROM phpdockerio/php80-fpm:latest

WORKDIR /application

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y imagemagick libzip-dev libpng-dev nano

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git

# Install selected extensions and other stuff
RUN apt-get -y install php8.0-pgsql php8.0-mysql php8.0-sqlite php8.0-redis php8.0-bcmath php8.0-gd php8.0-imagick php8.0-dev

COPY ./docker/dockerfiles/php-fpm/php-ini-overrides.ini /etc/php/8.0/fpm/conf.d/99-overrides.ini
COPY ./ /application

RUN rm -rf /application/docker && chmod -R 777 /application/bootstrap && chmod -R 777 /application/storage
