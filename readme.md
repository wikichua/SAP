# Simple Admin Panel (CRUD generator) - Basic (TO BE ENHANCED)

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

## Requirements

1. A new Laravel related project (completedly new)
2. Composer require laravel/ui (no need installing the auth scaffolding)
3. A working NPM in your machine
4. Knowledge in jQuery, Bootstrap, Axios, Sass, Lodash & all Laravel stuffs...

### Current Version

1. Theme using https://startbootstrap.com/themes/sb-admin-2/
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
1. Pusher
1. PHP Debug Bar
1. User Impersonate
1. Log Viewer (I call it system loging)
1. Chatify (Forked from https://github.com/munafio/chatify)
1. Global Search using ~Elastic Search (https://packagist.org/packages/elasticsearch/elasticsearch)~ Temporarily remove elasticsearch due to indexing issue. Looking into scout on elasticsearch driver..
1. Advanced filter prebuild text, date range and select
1. Socialite (support github, linkedin, google, facebook, twitter)
1. File Manager (https://github.com/UniSharp/laravel-filemanager)
1. Generate Brand Site with Subdomain
1. Component Management with try it online
1. Scout on elasticsearch driver (https://github.com/matchish/laravel-scout-elasticsearch)

### Todo List
1. Queue Manager (Laravel Horizon is cool, but...)

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
    use \Wikichua\SAP\Http\Traits\AllModelTraits;
    use \Laravel\Sanctum\HasApiTokens;
    use \Lab404\Impersonate\Models\Impersonate;
```

Then you continue in your terminal

```bash
$ php artisan migrate
$ php artisan ziggy:generate resources/js/ziggy.js
$ npm install --save && npm run dev
```

> php artisan vendor:publish --tag=sap.install --force

You will see this..

```bash
Copied File [/packages/wikichua/sap/resources/views/sap/components/menu.blade.php] To [/resources/views/vendor/sap/components/menu.blade.php]
Copied Directory [/packages/wikichua/sap/resources/views/sap/components/menus] To [/resources/views/vendor/sap/components/menus]
Copied Directory [/packages/wikichua/sap/resources/js] To [/resources/js]
Copied Directory [/packages/wikichua/sap/resources/sass] To [/resources/sass]
Copied File [/packages/wikichua/sap/package.json] To [/package.json]
Copied File [/packages/wikichua/sap/webpack.mix.js] To [/webpack.mix.js]

```

Scary but yeah, it does overwrited if you already had modified those in your files (Suggest to backup those before publishing with --force):

1. resources/js/app.js
1. resources/js/bootstrap.js
1. webpack.mix.js

Optional Publishing..

If you wish to ammend the auth layout at your wish or even change the admin theme layout..

> php artisan vendor:publish --tag=sap.view

What about the config? Namespaces?

> php artisan vendor:publish --tag=sap.config

However, I prefer to use sap.config to toggle the availability for auth routes.

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
art vendor:publish --tag=sap.install --force && art ziggy:generate && npm run watch-poll
```

##### Create a brand within your project?

> php artisan sap:brand SubDomain --domain=sub.domain.com

You should be seeing this...

```bash
Assets copied: /project/resources/views/brand/subDomain/assets
Web file created: /project/resources/views/brand/subDomain/web.php
Controller file created: /project/app/Http/Controllers/Brand/SubDomainController.php
layouts file created: /project/resources/views/brand/subDomain/layouts/main.blade.php
pages file created: /project/resources/views/brand/subDomain/pages/index.blade.php
package.json file created: /project/resources/views/brand/subDomain/package.json
webpack.mix.js file created: /project/resources/views/brand/subDomain/webpack.mix.js
.gitattributes copied: /project/resources/views/brand/subDomain/.gitattributes
.gitattributes copied: /project/resources/views/brand/subDomain/.gitignore
Migration file created: /project/database/migrations/2020_08_18_000000_sapSubDomainSeeding.php
If you are using valet...
Run this...
cd ./public
valet link sub.domain.com
valet secure

```

Oh ya... don't forget the migration

##### Surely need component

> php artisan sap:comp NewComponent

And sure you will see this...

```bash
Component created successfully.
Migration file created: /project/database/migrations/2020_08_19_000000_sapNewComponentComponentSeed.php
```

Oh ya... don't forget the migration

##### To exclude php debugbar render on ur API url

At your debugbar.php

```php
    'except' => [
        'telescope*',
        'horizon*',
        'chatify*',
        'api*',
    ],
```

##### Elastic Search...
Then in your all models
```php
protected $searchableFields = [];
```
Import your data into elastic search seemlessly
```bash
php artisan sap:import
```

##### Social Lite

In your User.php model
```php
protected $casts = [
    'social' => 'array',
];
```
Then
```dotenv
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=

FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=

LINKEDIN_CLIENT_ID=
LINKEDIN_CLIENT_SECRET=

TWITTER_CLIENT_ID=
TWITTER_CLIENT_SECRET=

```

##### In case munafio/chatify yet supporting latest pusher version...

```json
"repositories": {
        "munafio/chatify": {
            "type": "git",
            "url": "https://github.com/wikichua/chatify.git"
        }
    }
```

```json
"require": {
        "munafio/chatify": "dev-master",
    }
```

```bash
$ php artisan vendor:publish --tag=chatify-assets --force
```

## Usage

### Creating new module

```bash
$ php artisan sap:config <ModelName>
$ php artisan sap:make <ModelName>
$ php artisan ziggy:generate resources/js/ziggy.js
$ npm install && npm run dev
```

This is how I normall do

```bash
art ziggy:generate && npm run watch-poll
```

#### Config

You may get the sample of config file once sap:config called.
Do advise if that's confusing, I will then make a wiki.md for that.
In case of you getting "Config file is not ready".
This indicating your config generated from sap:config is still work in progress.

```php
    'ready' => false, // set true when you are ready to generate CRUD
```

Once you have done your config, you can switch that to true. Of course after sap:make is done, this section will be turned to true automatically.
This could prevent you from accidentally run sap:make again.

You may access with this auto populated login

> email: admin@email.com

> password: admin123

#### Deployment issue

Okay! I love ziggy but for now, there is no support env detection or either I can't figure out other way of hack technique.
But it's still good using it so all you need to do is

1. Change you .env file APP_URL to your production DNS
1. Npm run prod
1. Push to your repo and all set to go

#### Sample of the sap:make output

```bash
$ art sap:config Test
    Config file created: config/sap/TestConfig.php
$ art sap:make Test --force
    Config file created: config/sap/TestConfig.php
    Config Test Found! Initiating!
    Model file created: /project/app/Test.php
    API Route file created: routes/routers/testRoutes.php
    Controller file created: /project/app/Http/Controllers/Admin/TestController.php
    Menu included: routes/web.php
    View file created: /project/resources/views/admin/test/search.blade.php
    View file created: /project/resources/views/admin/test/index.blade.php
    View file created: /project/resources/views/admin/test/edit.blade.php
    View file created: /project/resources/views/admin/test/create.blade.php
    View file created: /project/resources/views/admin/test/show.blade.php
    View file created: /project/resources/views/admin/test/actions.blade.php
    Migration file created: /project/database/migrations/2020_08_04_000000_sapTestTable.php
    Since you had done make the CRUD, we will help you set ready to false to prevent accidentally make after you have done all your changes in your flow!
    Config has changed: /project/config/sap/TestConfig.php
```

#### Usage of Pusher (It's not broadcasting from Laravel original but...)
```php
    pushered('hello string');
    pushered(['hello array','hello array again']);
    pushered(['message' => 'hello message']);
    pushered(['message' => 'hello message']);
    pushered([
        'title' => 'hello title',
        'message' => 'hello message',
        'icon' => asset('your/logo'),
        'link' => 'http://link.com',
        'timeout' => 5000,
    ]);
```

## Security

If you discover any security related issues, please email wikichua@gmail.com instead of using the issue tracker.

## Credits

-   [Wiki Chua][link-author]
-   [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/wikichua/sap.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/wikichua/sap.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/wikichua/sap/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield
[link-packagist]: https://packagist.org/packages/wikichua/sap
[link-downloads]: https://packagist.org/packages/wikichua/sap
[link-travis]: https://travis-ci.org/wikichua/sap
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/wikichua
[link-contributors]: ../../contributors
