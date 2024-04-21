<h1 align="center">Order Service</h1>
<h3 align="center">A Single page Vue admin panel for Ordering.</h3>

## Built with
- [Laravel 11](https://github.com/laravel/framework)
- [spatie/laravel-permission](https://github.com/spatie/laravel-permission)
- [Laravel Breeze](https://github.com/laravel/breeze)
- [balajidharma/laravel-menu](https://github.com/balajidharma/laravel-menu)
- [Vue 3](https://vuejs.org/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Inertiajs](https://inertiajs.com/)
- [Admin One - Admin Dashboard](https://github.com/justboil/admin-one-vue-tailwind)

## Installation

### With Docker
- To get started, you need to install [Docker](https://www.docker.com/).
- You may run the following command in your terminal
- `git clone git@github.com:Hassanhs-97/order-service.git`
- `cd order-service/docker`
- `docker-compose up -d`
- `docker exec docker-backend-1 php artisan migrate:fresh --seed`
- Now open http://localhost:8008/login

### Without Docker Desktop
- To get started, you need to install [PHP Composer](https://getcomposer.org/).
- `git clone git@github.com:Hassanhs-97/order-service.git`
- `cd order-service`
- Create a new MYSQL database and update database details in `.env` file
- `php artisan key:generate`
- `php artisan migrate --seed`
- `npm install`
- `npm run dev`
- `php artisan serve`
- Now open http://localhost:8008/login

###### Super Admin Login
- Email - superadmin@example.com
- Password - password

#### Admin Configuration:

To change the Admin Prefix, change `prefix` on `config/admin.php` or add the `ADMIN_PREFIX` on env 

```php
'prefix' => env('ADMIN_PREFIX', 'admin'),
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
