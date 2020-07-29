# Simple Admin Panel (CRUD generator) - Basic (TO BE ENHANCED)

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

## Requirements

1. A new Laravel related project (completedly new)
2. Composer require laravel/ui (no need installing the auth scaffolding)
3. A working NPM in your machine
4. Knowledge in jQuery, Bootstrap, Axios, Sass, & all Laravel stuffs...

### Todo List

1. Activity Logging
1. Preset Authentication (exactly from Laravel scaffolding)
1. Permission & Role (Authorization)
1. Settings configuration
1. Basic Users' Management
1. Profile & Password Update
1. CRUD generator (create components for CRUD, migrations, forms, controller, model, etc...)
    - Datatable listing (using common table component in bootstrap)
        - Able to delete row record (Authorization Gate included)
    - Create and Edit form
        - Text, File, Textarea, Date or Time Picker, Select, Checkbox, Radio, Editor and etc...
1. Swal and Toast integrated
1. Select, Radio or Checkbox options will be added to settings table during migration
1. Select, Radio or Checkbox model options will be generated codes in controller and both create and edit component.
1. Select2

## Installation

Setup new Laravel project in terminal

```bash
$ laravel new **project**
$ php artisan storage:link
```

In your .env file

```env
APP_URL=https://**project.test**
DB_DATABASE=**project_table**
```

Back to your terminal

```bash
$ composer require wikichua/sap dev-master
$ composer require laravel/ui
$ php artisan vendor:publish --tag=sap.install --force
```

In your app/User.php

```php
class User extends \Wikichua\SAP\Models\User
{
    use Notifiable;

    use \Wikichua\SAP\Http\Traits\AdminUser;
    use \Wikichua\SAP\Http\Traits\ModelScopes;
    use \Wikichua\SAP\Http\Traits\DynamicFillable;
    use \Wikichua\SAP\Http\Traits\UserTimezone;
```

Then you continue in your terminal

```bash
$ php artisan migrate
$ php artisan ziggy:generate resources/js/ziggy.js
$ npm install --save --no-bin-links && npm run dev
```

> php artisan vendor:publish --tag=sap.install --force

You will see this..

```bash
Copied Directory [/vendor/wikichua/sap/resources/js] To [/resources/js]
Copied Directory [/vendor/wikichua/sap/resources/sass] To [/resources/sass]
Copied File [/vendor/wikichua/sap/package.json] To [/package.json]
Copied File [/vendor/wikichua/sap/webpack.mix.js] To [/webpack.mix.js]
```

Scary but yeah, it does overwrited if you already had modified those in your files (Suggest to backup those before publishing with --force):

1. resources/js/app.js
1. resources/js/bootstrap.js
1. webpack.mix.js

Optional Publishing..

If you wish to ammend the auth layout at your wish or even change the admin theme layout..

> php artisan vendor:publish --tag=vam.view

What about the config? Namespaces?

> php artisan vendor:publish --tag=vam.config

However, I prefer to use vam.config to toggle the availability for auth routes.

```php
'hidden_auth_route_names' => [
    'password_email' => false,
    'password_request' => false,
    'password_reset' => false,
    'password_confirm' => false,
    'login' => false,
    'register' => false,
    'logout' => false,
],
```

So if you wish to use your own route declarations, just turn all of those to **_true_**..

This is how I normall do

```bash
art vendor:publish --tag=vam.install --force && art ziggy:generate && npm run watch-poll
```

## Usage

### Creating new module

```bash
$ php artisan vam:config <ModelName>
$ php artisan vam:make <ModelName>
$ php artisan ziggy:generate
$ npm install && npm run dev
```

This is how I normall do

```bash
art ziggy:generate && npm run watch-poll
```

#### Config

You may get the sample of config file once vam:config called.
Do advise if that's confusing, I will then make a wiki.md for that.
In case of you getting "Config file is not ready".
This indicating your config generated from vam:config is still work in progress.

```php
    'ready' => false, // set true when you are ready to generate CRUD
```

Once you have done your config, you can switch that to true. Of course after vam:make is done, this section will be turned to true automatically.
This could prevent you from accidentally run vam:make again.

You may access with this auto populated login

> email: admin@email.com

> password: admin123

#### Deployment issue

Okay! I love ziggy but for now, there is no support env detection or either I can't figure out other way of hack technique.
But it's still good using it so all you need to do is

1. Change you .env file APP_URL to your production DNS
1. Npm run prod
1. Push to your repo and all set to go

#### Sample of the vam:make output

```bash
$ art vam:config Test
    Config file created: config/vam/TestConfig.php
$ art vam:make Test --force
    Config Test Found! Initiating!
    Model file created: /codes/vam/app/Test.php
    Controller file created: /codes/vam/app/Http/Controllers/Admin/TestController.php
    API Route file created: routes/routers/testRoutes.php
    VUE Route file created: /codes/vam/resources/js/routers/testRoutes.js
    Menu included: routes/web.php
    Vue component file created: /codes/vam/resources/js/components/test/ListComponent.vue
    Vue component file created: /codes/vam/resources/js/components/test/CreateComponent.vue
    Vue component file created: /codes/vam/resources/js/components/test/EditComponent.vue
    Vue component file created: /codes/vam/resources/js/components/test/ShowComponent.vue
    Menu included: routes/web.php
    Migration file created: /codes/vam/database/migrations/2020_06_05_000000_VamTestTable.php
    Since you had done make the CRUD, we will help you set ready to false to prevent accidentally make after you have done all your changes in your flow!
    Config has changed: /codes/vam/config/vam/TestConfig.php
```

## Security

If you discover any security related issues, please email wikichua@gmail.com instead of using the issue tracker.

## Credits

-   [Wiki Chua][link-author]
-   [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/wikichua/vam.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/wikichua/vam.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/wikichua/vam/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield
[link-packagist]: https://packagist.org/packages/wikichua/vam
[link-downloads]: https://packagist.org/packages/wikichua/vam
[link-travis]: https://travis-ci.org/wikichua/vam
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/wikichua
[link-contributors]: ../../contributors
