#!/bin/bash
set -e

echo "Deploying application ..."

# Enter maintenance mode
(php8.2-sp artisan down --message 'The app is being (quickly!) updated. Please try again in a minute.') || true
    # Update codebase
    git checkout development
    git reset --hard origin/development
    git pull origin development
    
    # Install dependencies based on lock file
    composer8.2-sp install --no-interaction --prefer-dist --optimize-autoloader
    
    # Migrate database
    php8.2-sp artisan migrate --force

    php8.2-sp artisan config:cache
    php8.2-sp artisan cache:clear
    php8.2-sp artisan optimize:clear

    #Queue Restart
    php8.2-sp artisan queue:restart
    php8.2-sp artisan queue:work --daemon > storage/logs/laravel.log &


# Exit maintenance mode
php8.2-sp artisan up

echo "Application deployed!"
