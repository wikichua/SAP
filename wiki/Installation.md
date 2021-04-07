## Installation

### Add SAP Access Token

```bash
composer config github-oauth.github.com 86e253009610b1ce0718f68da57b2a454a8d78e3
```

OR in auth.json

```json
{
    "http-basic": {
        "github.com": {
            "username": "wikichua",
            "password": "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"
        }
    }
}
```


### Add Repositories in your composer.json

Add this into your composer.json

```json
    "repositories": {
        "wikichua/sap": {
            "type": "vcs",
            "url": "https://github.com/wikichua/sap.git"
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

You probably need to export the config, but view is really up to you if you will need to make changes.

```bash
php artisan vendor:publish --tag=sap.export.config
php artisan vendor:publish --tag=sap.export.view
```

In your browser

Access to your https://***YourProject***.test/admin.
Email : admin@email.com
Password : admin123

Make sure you remove this from your web.php

```php
Route::get('/', function () {
    return view('welcome');
});
```
