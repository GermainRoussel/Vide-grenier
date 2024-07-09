#!/bin/sh

# Enter the directory containing the application code
cd /var/www/html

# Update composer dependencies
composer update

# Install composer dependencies
composer install

# Run PHPUnit tests
./vendor/bin/phpunit --testdox

# Start Apache in the foreground
apache2-foreground
