# LMS Menggunakan Laravel 11

<table>
    <tr>
        <td>
            <a href="https://laravel.com"><img src="https://i.imgur.com/pBNT1yy.png" /></a>
        </td>
    </tr>
</table>

## Requirements

You need to have PHP version **8.2** or above. Node.js version **20.0** or above.

## Installation

1. Clone the project
2. Go to the project root directory
3. Run `composer install` and `npm install && npm run dev`
4. Create database
5. Copy `.env.example` into `.env` file and adjust parameters (FILESYSTEM_DISK = public)
6. Run `php artisan migrate --seed`
7. Run `php artisan key:generate`
8. Run `php artisan serve` to start the project at http://127.0.0.1:8000

Username : author@gmail.com
<br>
Password : password

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
