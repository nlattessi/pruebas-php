FROM php:7.0-fpm
RUN docker-php-ext-install pdo_mysql
RUN usermod -u 1000 www-data
RUN usermod -G staff www-data
CMD ./docker/wait-for-it.sh mysql:3306 -t 0 -- php-fpm