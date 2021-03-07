FROM php:7.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    build-essential \
    zip \
    vim \
    curl

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring bcmath sockets

COPY . /var/www

EXPOSE 9000
CMD ["php-fpm"]
