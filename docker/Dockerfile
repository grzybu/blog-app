FROM php:8.0-alpine

RUN apk add --update \
		autoconf \
		g++ \
		libtool \
		make \
		&& pecl install pcov && \
            docker-php-ext-enable pcov
RUN docker-php-ext-install pdo_mysql
RUN rm -rf /tmp/* /var/cache/apk/*


COPY . /var/www/html
COPY .env.dist /var/www/html/.env

WORKDIR /var/www/html

EXPOSE 8080

CMD ["php", "-S", "localhost:8080", "/var/www/html/public/index.php"]
