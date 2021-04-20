FROM phpdockerio/php80-cli:latest

WORKDIR /application

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y imagemagick libzip-dev libpng-dev nano cron

# Install selected extensions and other stuff
RUN apt-get -y install php8.0-pgsql php8.0-mysql php8.0-sqlite php8.0-redis php8.0-bcmath php8.0-gd php8.0-imagick php8.0-dev \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

COPY ./ /application

RUN rm -rf /application/docker && chmod -R 777 /application/bootstrap && chmod -R 777 /application/storage

COPY ./docker/php_cron/main-cron /etc/cron.d/main-cron

RUN chmod 0644 /etc/cron.d/main-cron
RUN crontab /etc/cron.d/main-cron

CMD ["cron", "-f"]
