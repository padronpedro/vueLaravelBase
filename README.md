
## About this repository
 
This repository is the baseline for a Laravel + VueJs + JWT + vuetify project, including:
 
-  vue-axios.
-  vue-router.
-  vuetify.
-  vue-the-mask.
-  vue-i18n.
-  kirschbaum-development/laravel-translations-loader.
-  Also included login, register vue page
-  JWT configured in the backend

The main idea is: you can clone this project and have a basic project to start coding.

## How to use it

- Clone the repository
- npm install
- composer install
- php artisan key:generate
- php artisan jwt:secret
- chmod -R o+w storage
- chmod -R o+w bootstrap/cache/
- php artisan config:clear
- php artisan cache:clear
- php artisan view:clear
- php artisan route:clear
- composer dumpautoload
- php artisan migrate:refresh --seed
- npm run watch


## In progress

I will keep updating with more basic configurations.


## Contributing

Feel free to propose new packages or components, remember the idea is to have the basic ones to start any project.

  
## About me

I'm a web programmer passionate by coding.
