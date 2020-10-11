## Module Development

- [Create New Module with SAP](#Create-New-Module-with-SAP)
- [Create New Service](#Create-New-Service)
- [Create Service which belongs to Brand Only](#Create-Service-which-belongs-to-Brand-Only)
- [Create Export and Import Module with SAP](#Create-Export-and-Import-Module-with-SAP)
- [Create New Brand with SAP](#Create-New-Brand-with-SAP)
- [Create New Component with SAP](#Create-New-Component-with-SAP)
- [Create Component which belongs to Brand Only](#Create-Component-which-belongs-to-Brand-Only)
- [Using Pusher in your application](#Using-Pusher-in-your-application)
- [Run Import data to Elastic Search](#Run-Import-data-to-Elastic-Search)
- [Cleanup and Info Elastic Search Index](#Cleanup-and-Info-Elastic-Search-Index)
- [Run report with Artisan and Task Scheduler](#Run-report-with-Artisan-and-Task-Scheduler)
- [Queue and Cache Closure](#Queue-and-Cache-Closure)
- [PHP Debug Bar](#PHP-Debug-Bar)
- [Disable Artisan Command](#Disable-Artisan-Command)
- [Social Lite](#Social-Lite)

### Create New Module with SAP

**ModuleName** - MUST be Plural + Studly Case

#### Create Config

Run in your bash

```bash
php artsan sap:config *ModuleName*
```

[Config Sample](../stubs/config.stub)
Add or remove any configuration that doesn't need and set *ready* to *true*

#### Make Module

Run in your bash

```bash
php artsan sap:make *ModuleName*
```

Sample of result:
```bash
Config LinuxOs Found! Initiating!
Model file created: /var/www/l8/app/Models/LinuxOs.php
Route file created: routes/routers/linux_osRoutes.php
API Route file created: routes/routers/api/linux_osRoutes.php
Controller file created: /var/www/l8/app/Http/Controllers/Admin/LinuxOsController.php
Api Controller file created: /var/www/l8/app/Http/Controllers/Api/LinuxOsController.php
Menu included: routes/web.php
View file created: /var/www/l8/resources/views/admin/linux_os/search.blade.php
View file created: /var/www/l8/resources/views/admin/linux_os/index.blade.php
View file created: /var/www/l8/resources/views/admin/linux_os/edit.blade.php
View file created: /var/www/l8/resources/views/admin/linux_os/create.blade.php
View file created: /var/www/l8/resources/views/admin/linux_os/show.blade.php
View file created: /var/www/l8/resources/views/admin/linux_os/actions.blade.php
Migration file created: /var/www/l8/database/migrations/2020_09_17_000000_sapLinuxOsTable.php
Since you had done make the CRUD, we will help you set ready to false to prevent accidentally make after you have done all your changes in your flow!
Config has changed: /var/www/l8/config/sap/LinuxOsConfig.php
```

### Create New Service

**ServiceName** - MUST be Plural + Studly Case

Run in your bash

```bash
php artisan sap:service *ServiceName*
```

### Create Service which belongs to Brand Only

Run in your bash

```bash
php artisan sap:service *ServiceName* --brand=*BrandName*
```

You may like to add your business model as a service.

#### Create Export and Import Module with SAP

Coming soon

#### Using Pusher in your application

With Helper should be much more easy.

```php
    pushered('hello string');
    pushered(['hello array','hello array again']);
    pushered(['message' => 'hello message']);
    pushered([
        'title' => 'hello title',
        'message' => 'hello message',
        'icon' => asset('your/logo'),
        'link' => 'http://link.com',
        'timeout' => 5000,
    ]);
```

### Create New Brand with SAP

**BrandName** - MUST be Plural + Studly Case

Run in your bash

```bash
php artisan sap:brand *BrandName* --domain=*domain.test*
```

This brand will be scaffolded with the set of

1. Seeder for sample page, sample navigation, sample carousel and login modal (social lite)
2. Domain aliases (file domains.php) - *Need to clear cache for this*
3. Template using MDB (https://mdbootstrap.com)
4. Sample Page
5. Login Modal Component (include social lite for register)
```html
<x-{%brand_string%}::login-modal />
```
6. Top Navbar Login Component
```html
<x-{%brand_string%}::navbar-top-login />
```
7. Top Navbar Component
```html
<x-{%brand_string%}::navbar-top groupSlug="sample-navbar" />
```
8. Carousel Component
```html
<x-{%brand_string%}::carousel slug="sample-carousel" :tags="['new','hot']" />
```

More about [Brand Development](Brand-Development.md)

### Create New Component with SAP

Run in your bash

```bash
php artisan sap:comp *ComponentName*
```

### Create Component which belongs to Brand Only

Run in your bash

```bash
php artisan sap:comp *ComponentName* --brand=*BrandName*
```

### Run Import data to Elastic Search

In your scout.php

```php
    'driver' => env('SCOUT_DRIVER', null),
```

Run in your bash

```bash
php artisan sap:es
```

### Cleanup and Info Elastic Search Index

Run in your bash

Clean Up

```bash
curl -XDELETE 'localhost:9200/*_index_*'
```

Current Info

```bash
curl 'localhost:9200/_cat/indices?v'

```

### Run report with Artisan and Task Scheduler

```bash
php artisan sap:report
```

### Queue and Cache Closure

Coming Soon

### PHP Debug Bar

This feature is automatic enable in non production environment
However, you will need to exclude it in api related routes

At your debugbar.php

```php
    'except' => [
        'telescope*',
        'horizon*',
        'api*',
    ],
```

### Disable Artisan Command

In your app/Console/Kernel.php

```php
class Kernel extends ConsoleKernel
{
    use \Wikichua\SAP\Http\Traits\ArtisanTrait;
```

Then at the commands()

```php
    protected function commands()
    {
        $this->disable(['migrate:fresh', 'sap:config', 'sap:make'], ['production']);
```

Notes: 1st argument takes array for the command to be disabled while the 2nd argument is an array for environment that you want it to be run on.

### Social Lite

In your User.php model

```php
protected $casts = [
    'social' => 'array',
];
```

Then in your .env

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

Access to your https://***YourProject***.test/pub.
You should see another login page which mainly for public access instead of admin.
You should be seeing login with social media icons appearing based on the those credential filled for your provider.

As for your brand social lite login, you should hardcoded in the brand/**BrandName**/config/services.php
