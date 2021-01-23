## Brand Development

- [Init](#Init)
- [Config](#Config)
- [Route](#Route)
- [Middleware](#Middleware)
- [Service Provider](#Service-Provider)
- [Controller](#Controller)
- [Model](#Model)
- [View](#View)
- [NPM](#NPM)
- [Component](#Component)
- [Module](#Module)

### <a name="Init"></a>Init

**BrandName** - MUST be Plural + Studly Case

Run in your bash

```bash
php artisan sap:brand *BrandName* --domain=*domain.test*
```

### <a name="Config"></a>Config

By default, few files will be added

##### <a name="main.php"></a>main.php

```php
return [
    'resources_path' => base_path('brand/*brand_name*/resources/views'),
    'template_path' => base_path('brand/*brand_name*/resources/views/layouts'),

    'models' => [
        'user' => 'Brand\*BrandName*\Models\User'
    ],
];
```

In case will have to change the views directories

Add the namespaces of you model into models section for easy access via

```php
app(config('main.models.user'))->query()->all();
```

##### <a name="domains.php"></a>domains.php

Add aliases to allow more domain aliases pointed to main domain

```php
return [
    'main' => '*brand_name*.test',
    'aliases' => [
        // 'sub.*brand_name*.test'
    ],
];

```

##### <a name="auth.php"></a>auth.php

Basically this is the same concept as Laravel

Each brand should have it's own user table, auth guard, user model

##### <a name="services.php"></a>services.php

Mainly this is the extended for social lite configurations

### <a name="Route"></a>Route

Referring to web.php

By default it always redirected to **/en**

It's also require domain to fix access by hitting to correct domain. This helper will detect when hitting to alias domain as well.

```php
->domain(getDomain('*brand_name*'))
```

The main CMS post view is depend on group of routes in

```php
    Route::group([
            'prefix'=>'{locale?}',
            'middlewares' => ['*brand_name*:guest'],
            'where' => ['locale' => '[a-z]{2}']
        ],
        function () {
            Route::get('/', '*brandName*Controller@index')->name('home');
            Route::get('/{slug}', '*brandName*Controller@page')->name('page');
        });
```

NOTE: In order to ensure your new route added works, please use prefix name at minimum 3 chars. Example

```php
    Route::group(['prefix'=>'auth','middlewares' => ['*brand_name*:guest']], function () {
            Route::post('/login', 'LoginController@login')->name('login');
            Route::match(['get','post'], 'login/{provider}', 'LoginController@redirectToProvider')->name('social.login');
            Route::match(['get','post'], 'login/{provider}/callback', 'LoginController@handleProviderCallback')->name('social.callback');
            Route::get('logout', 'LoginController@logout')->name('logout');
        });
```

> 'prefix'=>'three_chars_at_least'

Or

> Route::any('three_chars_at_least/other_path_name')

### <a name="Middleware"></a>Middleware

By default I keep it clean as it is. This is good to run certain rules before hitting to the controller.

Such as, checking IP, obtain Affiliate info, redirecting to mobile or web url and etc...

### <a name="Service-Provide"></a>Service Provide

Not advisable to change unless you know what you need.

This is basically use for registering or deregister some settings or configuration that required to ensure the brand directory works as it should.

### <a name="Controller"></a>Controller

Freely to add this.

There is no artisan for this yet, but feel free to use

> php artisan make:controller *Sample*Controller

Then copy the file to your brand controllers directory and change the namespace accordingly

### <a name="Model"></a>Model

Freely to add this.

There is no artisan for this yet, but feel free to use

> php artisan make:model *Sample*

Then copy the file to your brand models directory and change the namespace accordingly

Advise to add this into your **main.php** in model section. This could allow easier access via

```php
app(config('main.models.*sample*'))->query()->all();
```

### <a name="View"></a>View

May refer to [Laravel Blade Document](https://laravel.com/docs/8.x/views)

##### <a name="Directory-Structure:"></a>Directory Structure:

1. layouts
1. pages
1. components

### <a name="NPM"></a>NPM

Suggest using webpack laravel mix.

Just simply run

> npm run dev

or

> npm run prod

This basically will perform npm install, php artisan ziggy:generate, then build that create public directory in the brand and symlink to your laravel application public directory

### <a name="Component"></a>Component

Make the component via artisan using

> php artisan sap:comp --brand="*BrandName*" ComponentName

If there is no brand option input, it will create at the laravel application level

Using this artisan command will include the "component seed" for the admin panel to keep track if the available component registered.

##### <a name="Usage"></a>Usage

```html
<x-*BrandName*::*component-name* />
```
### <a name="Module"></a>Module

Make the module via artisan using

#### <a name="Create-Config"></a>Create Config

Run in your bash

```bash
php artsan sap:config *ModuleName* --brand=*BrandName*
```

[Config Sample](../stubs/config.stub)
Add or remove any configuration that doesn't need and set *ready* to *true*

#### <a name="Make-Module"></a>Make Module

Run in your bash

```bash
php artsan sap:make *ModuleName* --brand=*BrandName*
```
