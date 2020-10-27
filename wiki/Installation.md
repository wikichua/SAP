## Installation

### Add SAP Access Token

```bash
composer config github-oauth.github.com 86e253009610b1ce0718f68da57b2a454a8d78e3
```

### Add Repositories in your composer.json

This package is using which currently not updated to be able to support Laravel 8 (I am not sure why, but...)
1. https://github.com/UniSharp/laravel-filemanager -> https://github.com/wikichua/laravel-filemanager.git
1. https://github.com/lionix-team/seo-manager -> https://github.com/wikichua/seo-manager.git

Add this into your composer.json

```json
    "repositories": {
        "wikichua/sap": {
            "type": "vcs",
            "url": "https://github.com/wikichua/sap.git"
        },
        "unisharp/laravel-filemanager": {
            "type": "git",
            "url": "https://github.com/wikichua/laravel-filemanager.git"
        }
    }
```

Run in your bash

```bash
mysql -uhomestead -p <<_EOF_
CREATE DATABASE *YourDatabase*;
_EOF_
laravel new *YourProject*
composer require laravel/ui
```

Ammend your .env

```env
APP_URL=https://*yourproject.test*
DB_DATABASE=*YourDatabase*
```

Run in your bash

```bash
composer require wikichua/sap:dev-master
composer require unisharp/laravel-filemanager:dev-master
php artisan storage:link
php artisan vendor:publish --tag=sap.install --force
```

In your app/User.php

```php
class User extends \Wikichua\SAP\Models\User
```

```php
    protected $casts = [
        'social' => 'array',
    ];
```

Run in your bash

```bash
php artisan migrate
npm run dev
```

Optional publishing, run in your bash

```bash
php artisan vendor:publish --tag=sap.view
php artisan vendor:publish --tag=sap.config
```

In your browser

Access to your https://***YourProject***.test/admin.
Email : admin@email.com
Password : admin123
