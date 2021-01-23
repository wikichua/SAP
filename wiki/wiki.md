# SAP - Simple Admin Panel

- [Introduction](#Introduction)
- [Requirements](#Requirements)
- [Features or Done](#Features-or-Done)
- [Todo List](#Todo-List)
- [Package Development](Package-Development.md)
- [Installation](Installation.md)
- [Module Development](Module-Development.md)
- [Brand Development](Brand-Development.md)
- [Available Components](Available-Components.md)
- [Available Helper](Available-Helper.md)
- [Refer](#Refer)

## <a name="Introduction"></a>Introduction

Laravel oriented package. This package is mainly for

1. Multisites - Create + manage multiple domain brand homepage + register to database for managing
1. Module - CRUD generator module for main app or for brand + API resources
1. Export & Import Module (Site specific - module created for site could be export and import to another site) via Artisan Console
1. Component - Create + manage component for main app or for brand + register to database for managing
1. CMS - Create + manage pages and navigations for brand
1. Reporting - Create + manage SQLs for reporting + console to run SQL within the queue (Redis)
1. Settings - Create + manage settings value(s) within the table (cached if enabled) + encryption for sensitive value
1. Global Search - Indexing for Search (Not elasticsearch, algolia and etc) - in case need, use laravel scout
1. ACL - Manage users, assign roles & permission, impersonating, check last activity details
1. Activity Logs - Helper to create logs in database
1. Failed Queue/Job - Retry on the platform itself, glance at the pending, notify, reserved, priority and delayed jobs count (Redis)
1. Security - Honeypot to prevent spamming. Settings data store as encrypted in database (manually trigger)
1. Model Events - Developer could push additions operation into *onCreatedEvent*, *onUpdatedEvent*, *onDeletedEvent*, *onCachedEvent* methods.
1. File Managers - App & Site specific. User will only get access to its own brand management. (auto optimizing images)
1. Cronjob Admin - Developer just need to write the script using Artisan Command, then commit. The CRUD have to be done in the Admin Panel.
1. Mail View Editor - Developer can create mailable using sap:mailer, content team could deploy the layout of the email body in HTML and Text.

## <a name="Requirements"></a>Requirements

1. A new Laravel related project (completedly new)
1. Composer require laravel/ui (no need installing the auth scaffolding)
1. Redis
1. Supervisord
1. A working NPM in your machine
1. Knowledge in jQuery, Bootstrap, Material Design Bootstrap, Axios, Sass, Lodash & all Laravel stuffs...

## <a name="Features-or-Done"></a>Features or Done

1. Theme using https://startbootstrap.com/themes/sb-admin-2/
1. Activity Logging (Censored sensitive data) + More data captured
1. Preset Authentication (exactly from Laravel scaffolding)
1. Permission & Role (Authorization)
1. Settings configuration
1. Basic Users' Management
1. Profile & Password Update
1. CRUD generator (create components for CRUD, migrations, forms, controller, model, etc...)
    - Datatable listing (using common table component in bootstrap)
        - Able to delete row record (Authorization Gate included)
    - Create and Edit form
        - Text, File, Textarea, Date or Time Picker, Select, Checkbox, Radio, Editor, Datalist, Markdown and etc...
1. Swal and Toast integrated
1. Select, Radio or Checkbox options will be added to settings table during migration
1. Select, Radio or Checkbox model options will be generated codes in controller and both create and edit component.
1. Pusher
1. PHP Debug Bar
1. User Impersonate
1. Log Viewer (I call it system loging)
1. Advanced filter prebuild text, date range and select
1. Socialite (support github, linkedin, google, facebook, twitter)
1. File Manager
1. Generate Brand Site with Subdomain
1. Component Management with try it online
1. Create page as your blog page
1. Personal Access Token
1. Report (NOT BI) - simple as fill the sql statements and save into table
1. Prerun reporting and store in cache
1. Prerun reporting "queue" or "sync" and store in cache
1. Fast Excel
1. Create Service Facade as supporting Library
1. Stopping certain artisan cli perform on certain environments
1. Page can choose different template (but template must be pre-coded)
1. Locale for page module
1. Locale for navigation menu
1. Navigation Management
1. Carousel component for brand
1. Tie user to brand
1. Let listing table resizeable, sticky table head
1. Sortable rows and update to seq field in table.
1. Alias Domain for Brand
1. Brand User Model
1. Brand middleware and auth (social lite login + register)
1. ~BUG : domains aliases issue~
1. ~BUG : social login not working~
1. ~BUG : cant load from route for multiple brands in brandserviceprovider~
1. Notification swal2 for brand (https://realrashid.github.io/sweet-alert/install)
    1. Convert alert.blade.php to brand component
1. ~MongoDB package - activity logs should be in mongodb (https://github.com/jenssegers/laravel-mongodb)~ Maybe NO
1. Brand Specific Module (in case of the module on specific for the brand)
1. Setting Module, some of the data need to be encrypted to prevent direct read from database table
1. Markdown editor (https://simplemde.com/)
1. Markdown parser (https://github.com/ARCANEDEV/LaravelMarkdown)
1. Global Searchable refined (remove elasticsearch dependency)
1. Observer for models to handle searchable data
1. Brand's Model specific, Brand DB Connection specific.
1. IP Location
1. Last Activity show by user and profile
1. Queue Manager (Laravel Horizon is cool, but...Decided to do self made)
1. Honeypot to secure from spammer (https://github.com/spatie/laravel-honeypot)
1. Brand specific file manager - check user brand, only access to their own brand folder.
1. Module Import & Export (Brand to another Brand)
1. Cronjob Admin
1. Middleware optimize image
1. Mailer body layout manager

## <a name="Todo-List"></a>Todo List

1. Caching Response (https://github.com/spatie/laravel-responsecache)
1. Mailer Editor (https://github.com/Qoraiche/laravel-mail-editor)
1. Brand specific file manager - cloud based
1. HTML Builder? (https://grapesjs.com/docs/)
1. SEO Manager (https://github.com/lionix-team/seo-manager)
1. Surveillance (https://github.com/neelkanthk/laravel-surveillance)
1. Integration with Clockwork (https://underground.works/clockwork/#documentation)
1. Easily integrated with internal microservice or external microservice by lumen or any framework.
1. Web Terminal seem cool and fun (https://github.com/recca0120/laravel-terminal)
1. Better Form Builder? (https://airephp.com/)

## <a name="Refer"></a>Refer

1. https://startbootstrap.com/themes/sb-admin-2/
1. https://developer.snapappointments.com/
1. https://sweetalert2.github.io/
1. https://unsplash.com/
1. https://lodash.com/
1. https://learn.co/lessons/javascript-lodash-templates
1. https://github.com/axios/axios
1. https://github.com/tighten/ziggy
1. https://gijgo.com/
1. http://www.daterangepicker.com/
1. https://summernote.org/
1. https://codemirror.net/
1. https://momentjs.com/
1. https://pushjs.org/
1. https://mdbootstrap.com
1. https://bootstrap-table.com
1. https://github.com/UniSharp/laravel-filemanager
1. https://github.com/rap2hpoutre/fast-excel
1. https://realrashid.github.io/sweet-alert
1. https://github.com/jenssegers/laravel-mongodb
1. https://github.com/sparksuite/simplemde-markdown-editor
1. https://github.com/ARCANEDEV/LaravelMarkdown
1. https://github.com/spatie/laravel-honeypot
1. https://underground.works/clockwork/#documentation
1. https://github.com/spatie/laravel-image-optimizer
1. https://github.com/spatie/laravel-responsecache
1. https://github.com/spatie/laravel-database-mail-templates
1. https://github.com/envault/envault
