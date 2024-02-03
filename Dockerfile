FROM ubuntu:22.04
# FROM --platform=linux/amd64 ubuntu:22.04

# make the 'app' folder the current working directory
WORKDIR /var/www/html

# install packages
RUN apt-get update -y && \
    apt-get install -y gnupg2 && \
    apt-get install -y libpng-dev && \
    apt-get install -y cron && \
    apt-get install -y curl && \
    apt-get install -y redis-server && \
    apt-get install -y software-properties-common && \
    add-apt-repository ppa:ondrej/php && \
    apt-get install -y supervisor && \
    apt-get install -y vim && \
    apt-get install -y php8.3-cli  php8.3-fpm php8.3-curl php8.3-gd php8.3-mbstring zip unzip php8.3-mysql && \
    apt-get install mysql-client -y && \
    apt-get install php8.3-mongodb -y && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/bin --filename=composer

# installing project dependencies
RUN apt-get update && apt-get install -y \
        php8.3-intl \
        php8.3-xml \
        php8.3-gd \
        php8.3-mbstring \
        php8.3-zip \
        php8.3-bcmath \
        # nodejs \
        # npm \
        # apache2 \
        nginx \
        libapache2-mod-php8.3

#COPY . . test
# COPY app.conf /etc/apache2/sites-available/
# COPY apache2.conf /etc/apache2/apache2.conf
COPY app.conf /etc/nginx/sites-enabled/default
COPY queue-worker.conf /etc/supervisor/conf.d/queue-worker.conf
COPY horizon.conf /etc/supervisor/conf.d/horizon.conf

# This php.ini file has updated memory_limit
COPY phpini.conf /etc/php/8.0/fpm/php.ini
#RUN composer update
#RUN composer install

CMD ["php-fpm"]
#ENTRYPOINT ["/bin/sh","/var/www/html/script.sh"]

