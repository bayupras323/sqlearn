<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About SQLearn Project 2023

The SQLearn Project 2023 is an ambitious software development venture that seeks to augment students' relational database learning experience. It is a natural progression from the highly successful SQLearn 2022, which developed an innovative, interactive platform utilizing drag-and-drop technology to assist students in query construction.

The thrust of SQLearn 2023 is geared towards the reconstruction of query execution results and Entity Relationship Diagrams (ERD). The program's focus is to furnish learners with a holistic and profound grasp of how relational databases operate by delivering an immersive and captivating learning environment.

Through an amalgamation of interactive tools and practical exercises, SQLearn Project 2023 aims to simplify the understanding and application of relational database concepts. The platform will provide a real-time practice arena for students to create queries and visualize query execution results. Moreover, students will be able to sharpen their skills in constructing ERDs, which represent the relationships between entities in a database.

Overall, the SQLearn Project 2023 represents a thrilling advancement in relational database education, offering a more dynamic and interactive pedagogy that enables students to comprehend and employ the principles of database management with greater ease.

## Prerequisites

The following are the prerequisites to run this project.

**Note: You can skip step 2-6.**

1. Clone Repo / Pull
2. Install composer

    ```bash
    composer install
    ```

3. Update composer

    ```bash
    composer update
    ```

4. Install node modules

    ```bash
    npm install
    ```

    or

    ```bash
    npm i
    ```

5. To clear all cache

    ```bash
    php artisan optimize:clear
    ```

6. To migrate fresh database with seed

    ```bash
    php artisan migrate:fresh --seed
    ```

7. Alternatively can use the following command to chain all the commands together

    ```bash
    composer install; composer update; npm install; php artisan optimize:clear; php artisan migrate:fresh --seed
    ```

8. To start the server

    ```bash
    php artisan serve
    ```

9. To run the test using cypress

    ```bash
    npx cypress open
    ```

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
