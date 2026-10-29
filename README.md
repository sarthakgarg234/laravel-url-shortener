# Laravel URL Shortener

A role-based URL shortener built with Laravel 12 and MySQL.

## Features
- Login and logout
- Company and user role management
- Roles: SuperAdmin, Admin, Member, Sales, Manager
- Role-based URL creation restrictions
- Invitation restrictions by role
- Short URLs do not publicly redirect
- Laravel validation and CSRF protection

## Requirements
- PHP and required Laravel extensions
- Composer
- MySQL

## Setup
1. Clone this repository.
2. Run `composer install`.
3. Copy `.env.example` to `.env`.
4. Configure the MySQL connection in `.env`.
5. Run `php artisan key:generate`.
6. Run `php artisan migrate`.
7. Set `SUPERADMIN_EMAIL` and `SUPERADMIN_PASSWORD` in `.env`.
8. Run `php artisan db:seed`.
9. Start the server with `php artisan serve`.

## Tests
Run `php artisan test`.

## AI Assistance
AI tools assisted with implementation and documentation. Review and test the code before production use.
