FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libcurl4-openssl-dev\
    openssl\
    libonig-dev\
    libzip-dev\
    libxml2-dev\
    zip\
    pkg-config\
    libssl-dev\
    libmcrypt-dev &&\
    apt-get clean &&\
    rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY . /app

RUN docker-php-ext-install  bcmath  soap pdo_mysql  exif pcntl zip  sockets  ctype fileinfo mysqli pdo pdo_mysql shmop
RUN docker-php-ext-configure gd
RUN docker-php-ext-install gd
RUN docker-php-ext-enable mysqli

RUN curl -sL https://deb.nodesource.com/setup_21.x | bash -
RUN apt-get update && apt-get install -y nodejs

COPY package.json /app/package.json
COPY package-lock.json /app/package-lock.json

RUN npm install
RUN npm run build

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN chown -R www-data:www-data /app
RUN chmod 755 /app

EXPOSE 8005