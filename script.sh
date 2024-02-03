#!/bin/bash
/etc/init.d/php8.0-fpm start
composer install
# chmod -R 777 /var/www/html
chmod -R 777 /var/www/html/vendor
chmod -R 777 /var/www/html/storage

php artisan key:gen
php artisan migrate
# npm install 
# service supervisor start
# service redis-server start
# supervisorctl reread
# supervisorctl update
# supervisorctl start queue-worker:*
# supervisorctl start horizon:*
# supervisorctl status queue-worker:*

service cron start
service nginx start

crontab /var/www/html/crontabs

# ++++++++++++ Install aws cli +++++++++++++
# curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
# unzip awscliv2.zip
# ./aws/install
# ./aws/install -i /usr/local/aws-cli -b /usr/local/bin
# which aws
# aws --version
# rm awscliv2.zip
# rm -rf aws
# ++++++++++++ Install aws cli +++++++++++++

service nginx restart
while true; do sleep 1d; done