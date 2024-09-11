FROM php:8.3-fpm
WORKDIR "/application"

ARG CRONTAB_NAME="crontab"

# Install selected extensions and other stuff
RUN apt-get update && \
    apt-get install -y \
      libxml2-dev \
      librabbitmq-dev

## Install AMqp
RUN pecl install amqp && \
    docker-php-ext-enable amqp

RUN apt-get update \
    && apt-get -y --no-install-recommends install php8.2-mysql \
    && apt-get -y --no-install-recommends install php8.2-fpm \
    && apt-get -y --no-install-recommends install php8.2-intl \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* \
    && docker-php-ext-install  \
        pdo \
        pdo_mysql \
	# Install Composer
    && curl \
        --location \
        --silent \
        --show-error \
        https://github.com/composer/composer/releases/download/2.2.5/composer.phar \
        > \
        /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

COPY . .

RUN composer self-update

RUN apt-get update
RUN apt-get -y install zip

CMD php-fpm