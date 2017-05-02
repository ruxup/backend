FROM php:7.0.4-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev \
    mysql-client libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install mcrypt pdo_mysql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN alias composer="php -n /usr/local/bin/composer"

# Create app directory
WORKDIR /var/www
RUN chmod 757 /var/www

# Install app dependencies
COPY composer.json /var/www/
COPY composer.lock /var/www/

RUN cd /var/www/
ADD . /var/www/

# Bundle app source
COPY . /var/www/

RUN composer update
