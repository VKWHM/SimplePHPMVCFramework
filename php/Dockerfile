FROM composer
RUN docker-php-ext-install pdo pdo_mysql
WORKDIR /app
CMD cd public && php -S 0.0.0.0:8000
