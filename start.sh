#!/bin/bash

chown -R www-data:www-data /var/www/html/image

source /etc/apache2/envvars
exec apache2 -D FOREGROUND
