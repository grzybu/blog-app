FROM php:7.4-alpine

RUN docker-php-ext-install pdo_mysql

COPY . /var/www/html

WORKDIR /var/www/html

EXPOSE 8080

CMD ["php", "-S", "localhost:8080", "/var/www/html/public/index.php"]
