FROM php:8.2-fpm

RUN set -eux; \
	apt-get update; \
	apt-get install -y --no-install-recommends \
		unzip \
	; \
	rm -rf /var/lib/apt/lists/*

RUN set -eux; \
	docker-php-ext-install pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer