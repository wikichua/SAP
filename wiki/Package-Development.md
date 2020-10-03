###Package Development

***Personal Use Only***

Run in your bash

```bash
laravel new *YourProject*
composer require jeroen-g/laravel-packager dev-master
php artisan packager:git git@github.com:wikichua/sap.git
```

Append this in your composer.json under repositories section

```json
        "unisharp/laravel-filemanager": {
            "type": "git",
            "url": "https://github.com/wikichua/laravel-filemanager.git"
        },
        "lionix/seo-manager": {
            "type": "git",
            "url": "https://github.com/wikichua/seo-manager.git"
        }
```

Run in your bash

```bash
composer require wikichua/sap:dev-master
composer require unisharp/laravel-filemanager:dev-master
composer require lionix/seo-manager:dev-master
php artisan storage:link
```
