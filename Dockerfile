FROM php:8.2-fpm

RUN set -eux; \
	apt-get update; \
	apt-get install -y --no-install-recommends \
		unzip \
	; \
	rm -rf /var/lib/apt/lists/*

RUN set -eux; \
	docker-php-ext-install pdo_mysql

RUN docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-install pcntl;

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions amqp

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer