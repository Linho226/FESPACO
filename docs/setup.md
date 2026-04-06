# FESPACO setup

This project is a PHP/Laravel application.

## System prerequisites

- PHP 8.2 or newer
- Composer 2.x
- Node.js 20.x and npm
- SQLite, MySQL, or PostgreSQL

## Common PHP extensions

- OpenSSL
- PDO
- Mbstring
- Tokenizer
- XML
- Ctype
- JSON
- BCMath
- Fileinfo

## Local installation

1. Install PHP dependencies:
   `composer install`
2. Install frontend dependencies:
   `npm install`
3. Create the environment file:
   `cp .env.example .env`
4. Generate the application key:
   `php artisan key:generate`
5. Run migrations:
   `php artisan migrate`
6. Build frontend assets:
   `npm run build`

## Development

Start the local development stack with:

`composer run dev`

## Notes

- Dependencies are managed with `composer.json` and `package.json`.
- Do not recreate a root `requirements.txt`; Render uses it to detect Python projects.