## Requirements

- Php 7 
- Mysql
- Composer

## How to install 

First step you must create `.env` file from example env file. This is file including in project.
```
cp .env.example .env
```
Second step create database and add to env file.
```
DB_NAME=database_name_here
```
Then you must run `composer install` command from terminal and migration command with seeders.
```
php artisan migrate --seed
```
Done. You can start project with `php artisan serve` command.

