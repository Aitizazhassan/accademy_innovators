#!/bin/bash
set -e

echo "Deploying application ..."

# Enter maintenance mode
(php artisan down --message 'The app is being (quickly!) updated. Please try again in a minute.') || true
    # Update codebase
    # git checkout development
    # git reset --hard origin/development
    # git pull origin development
    
    # Install dependencies based on lock file
    composer install --no-interaction --prefer-dist --optimize-autoloader
    
    # Migrate database
    php artisan migrate --force

    php artisan config:cache
    php artisan cache:clear
    php artisan optimize:clear

    #Queue Restart
    php artisan queue:restart
    php artisan queue:work --daemon > storage/logs/laravel.log &


# Exit maintenance mode
php artisan up

echo "Application deployed!"
