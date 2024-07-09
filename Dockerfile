FROM php:7.4-apache

# Install necessary packages
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    vim \
    git \
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

# Set the working directory
WORKDIR /var/www/html

# Use build arguments to pass the GitHub token and branch
ARG GITHUB_TOKEN
ARG BRANCH

# Clone the repository
RUN git clone -b ${BRANCH} https://${GITHUB_TOKEN}@github.com/GermainRoussel/Vide-grenier .

# Copy necessary configuration files
COPY Docker-vhost.conf /etc/apache2/sites-enabled/docker-vhost-wp.conf
COPY composer.json /var/www/html/composer.json
COPY package.json /var/www/html/package.json

# Install dependencies
RUN composer install
RUN npm install

# Ensure proper permissions for the web server
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Restart Apache to apply changes
RUN service apache2 restart

# Start Apache in foreground mode
CMD ["apache2-foreground"]
