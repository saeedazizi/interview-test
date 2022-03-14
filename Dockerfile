FROM php:8.0.3-fpm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install dependencies
RUN apt-get --allow-releaseinfo-change update  && apt-get install -yq --fix-missing \
    build-essential \
    libpng-dev \
    libonig-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libmcrypt-dev \
    zlib1g-dev \
    libxml2-dev \
    libzip-dev \
    libcurl4 \
    libcurl4-openssl-dev \
    locales \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    vim \
    git \
    curl \
    gnupg2 \
    libssh-dev

# Install extensions
RUN docker-php-ext-install zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install curl

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"

COPY . /var/www

# Set working directory
WORKDIR /var/www

CMD ["php-fpm"]
