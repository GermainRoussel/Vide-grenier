FROM php:8.0.2-apache

# Modules apache
RUN a2enmod headers deflate expires rewrite
EXPOSE 80

# Install necessary packages
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    vim \
    python2 \
    python2-dev

# Symlink Python to python for node-gyp
RUN ln -s /usr/bin/python2 /usr/bin/python

# Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Extensions MySQL pour PHP/WordPress
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Imagick pour WordPress
RUN apt-get install -y libmagickwand-dev --no-install-recommends && rm -rf /var/lib/apt/lists/*
RUN printf "\n" | pecl install imagick
RUN docker-php-ext-enable imagick

# Install Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_14.x | bash - && \
    apt-get install -y nodejs

# Verify npm installation
RUN npm --version
RUN node --version

# Copy application source
COPY . /var/www/html/

# Virtualhost
COPY Docker-vhost.conf /etc/apache2/sites-enabled/docker-vhost-wp.conf
COPY composer.json /var/www/html/composer.json
COPY package.json /var/www/html/package.json

RUN chown -R www-data:www-data /var/www/html/public && \
    chmod -R 755 /var/www/html/public
    
# Set the ServerName globally in Apache configuration
RUN echo "ServerName localhost" >> /etc/apache2/apache2-servername.conf

# Ensure Apache serves index.php as a directory index
RUN echo 'DirectoryIndex index.php index.html' >> /etc/apache2/conf-available/docker-php.conf
RUN a2enconf docker-php

# Install dependencies
WORKDIR /var/www/html/
RUN composer update
RUN composer install
#RUN npm install
#RUN npm run watch


# Ensure proper permissions for the web server
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Copy the start script
COPY start.sh /start.sh
RUN chmod +x /start.sh


# Restart Apache to apply changes
RUN service apache2 restart

# # Set the entrypoint
# ENTRYPOINT ["/start.sh"]
# Démarrer Apache en mode premier plan
CMD ["apache2-foreground"]
