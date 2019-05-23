FROM php:7.2.18-apache

# enable mod_rewrite in apache
RUN a2enmod rewrite

# copy the apache-config to the default enabled configuation
COPY apache-config.conf /etc/apache2/sites-enabled/000-default.conf

# copy the solution into the working apache directory
COPY . /var/www/

# make the var directory read writeable by the webserver
RUN chown www-data.www-data -R /var/www/var/

EXPOSE 80