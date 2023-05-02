# Create Laravel Project

> composer create-project laravel/laravel ourfirstsapp

# Course source

https://www.udemy.com/course/lets-learn-laravel-a-guided-path-for-beginners

# Original Git

https://github.com/LearnWebCode/laravel-course

# This git

https://github.com/samedan/2304_Laravel_10

# Markdown Cheat Sheet

https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet

# Clear cache in Views

> php artisan view:clear

# MIGRATIONS

> php artisan migrate
> php artisan migrate:fresh
> php artisan make:migration add_favourite_color_column

# Add Column to existing table

> php artisan make:migration add_isadmin_to_users_table --table=users

# New Post

> php artisan make:controller PostController
> php artisan make:migration create_posts_table
> php artisan make:model Post

# New Policy

> php artisan make:policy PostPolicy --model=Post

# Shortcut to /storage Avatar folder

> php artisan storage:link

> Files are stored in a folder in the /storage/avatar and there is
> a link to that folder in the 'public' folder visible from outside

> fallback avatar image is defined in User.php model

## Follow

> php artisan make:migration create_follows_table
> php artisan make:controller FollowController
> php artisan make:model Follow

## use Bootstrap for Pagination

> UserController -> showCorrectHomepage -> pagination(4)
> homepage-feed -> {{$posts->links()}}
> /app/Providers/AppServiceProviders

# Pass 'props' into views

> /views/components/post.blade.php
> hideAuthor in profile-posts.blade

# Seed New data into the Database

> php artisan db:seed > only adds data
> php artisan migrate:fresh --seed > deletes old data

## Search with Laravel Scout

> composer require laravel/scout
> php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
> 'use Searchable', 'function toSearchableArray' on the Post model
> in .env add 'SCOUT_DRIVER=database'

## Create event & listener

> in EventServiceProvider add lines:
> use App\Events\OurExampleEvent;
> use App\Listeners\OurExampleListener;

> php artisan event:generate

> 1. UserController add event(new ExampleEvent)
> 2. ExampleEvent \_\_construct($theEvent) with the passed data
> 3. ExampleListener handle($event), pass Log-> laravel.log

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

-   **[Vehikl](https://vehikl.com/)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Cubet Techno Labs](https://cubettech.com)**
-   **[Cyber-Duck](https://cyber-duck.co.uk)**
-   **[Many](https://www.many.co.uk)**
-   **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
-   **[DevSquad](https://devsquad.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
-   **[OP.GG](https://op.gg)**
-   **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
-   **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
