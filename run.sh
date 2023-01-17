#!/bin/bash
php artisan queue:work 
php artisan migrate
php artisan db:seed 
npm run build
php artisan optimize:clear
php artisan serve