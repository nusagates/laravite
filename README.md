<p>Laragates is a Laravel Starter Pack Project that uses Vite, VueJS, and Vuetify as the building blocks for the front-end.</p>

## Development Goal

Makes it easy for developers to start laravel projects without having to do basic configuration every time they start a
new project. Vite, VueJS, Vuetify, Material Design Icons, and other things that are generally needed are already set up
in Laragates so developers can just use them.

## Project Specification

This project uses the following combat tools:

- Laravel v9
- Vite v3
- VueJs v3
- Vuetify v3
- Material Design Icon v7

## Screencast

[![Screencast](http://img.youtube.com/vi/IcrqdpMc4LQ/0.jpg)](http://www.youtube.com/watch?v=IcrqdpMc4LQ "Laragates Demo")

## Screenshot
*Registration Page* <br>
![Screenshot_1](/public/assets/images/Screenshot_1.png?raw=true)

*Login Page*<br>
![Screenshot_2](/public/assets/images/Screenshot_2.png?raw=true)

*User Manager*<br>
![Screenshot_3](/public/assets/images/Screenshot_3.png?raw=true)
*Add new Role*<br>
![Screenshot_4](/public/assets/images/Screenshot_4.png?raw=true)
*Update Account*<br>
![Screenshot_5](/public/assets/images/Screenshot_5.png?raw=true)

## Installation

Clone Laragates repository

``` bash
git clone https://github.com/nusagates/laragates.git project-name
```

Install dependency

```bash
composer install
```

Install NPM modules

```bash
npm install
```

Rename ``.env.example`` file to ``.env`` and change database configs. Then run migration

```bash
php artisan migrate
```

Generate APP key

```bash
php artisan key:generate
```
Add dummy data and required role & permissions

```bash
php artisan db:seed
```
Start development.

```bash
npm run dev
```

## Auth Credentials
An user created by default is:<br>
email: <code>nusagates@gmail.com</code><br>
pass: <code>123456</code>

Registration url: <code>baseurl/register</code><br>
Login url: <code>baseurl/login</code><br>
## Support

If there are problems or questions, please contact us via

- Telegram: https://t.me/cakbud
- Instagram: https://www.instagram.com/cakbood/
- email: nusagates[ad]gmail[dot]com
