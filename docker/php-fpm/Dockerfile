FROM phpdockerio/php80-fpm:latest

WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive
ENV BUILD_DEPS 'autoconf build-essential git gcc g++ make zlib1g-dev libssl-dev php-pear libssl1.1 openssl'

RUN apt-get update && apt-get install -y imagemagick libzip-dev libpng-dev nano

# Install selected extensions and other stuff
RUN apt-get -y install php8.0-pgsql php8.0-mysql php8.0-sqlite php8.0-redis php8.0-bcmath php8.0-gd php8.0-gmp php8.0-imagick php8.0-dev \
    && apt-get -y --no-install-recommends install apt-utils librdkafka-dev librdkafka++1 librdkafka1 ${BUILD_DEPS}

RUN pecl install rdkafka \
    && echo "extension=rdkafka.so" > /etc/php/8.0/cli/conf.d/10-rdkafka.ini \
    && echo "extension=rdkafka.so" > /etc/php/8.0/fpm/conf.d/10-rdkafka.ini \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

COPY ./ /application

RUN rm -rf /application/docker && chmod -R 777 /application/bootstrap && chmod -R 777 /application/storage

