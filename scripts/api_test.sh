#!/bin/sh

# Artisan migrate
php artisan migrate:refresh

# Run API tests
/app/vendor/bin/phpunit -c /app/tests/api-contract/phpunit.xml