# Laravel Auth Boiler plate

## Installation
**First clone the ripo**

1.Setup The .env file
````shell
ren .env.example .env
````
2. Install the package via composer:
```shell
composer install
```

3. Run the migration
```shell
php artisan migrate
```

4. Create a admin and user role 
```shell
php artisan permission:create-role user
php artisan permission:create-role admin
```
5. Now create a user with role admin
```shell
  php artisan create:admin
```
