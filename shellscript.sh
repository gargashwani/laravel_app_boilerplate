#!/bin/bash
/etc/init.d/php8.0-fpm start
composer update
php artisan key:gen
service supervisor start
supervisorctl reread
supervisorctl update
supervisorctl start queue-worker:*
supervisorctl status queue-worker:*
service cron start
service nginx start
chmod -R 777 /var/www/html
chmod -R 777 /var/www/html/vendor
chmod -R 777 /var/www/html/storage
crontab /var/www/html/crontabs
service nginx restart
while true; do sleep 1d; done