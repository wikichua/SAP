# SAP - Simple Admin Panel

- [Introduction](#Introduction)
- [Requirements](#Requirements)
- [Features](#Features)
- [Todo List](#Todo-List)
- [Package Development](Package-Development.md)
- [Installation](Installation.md)
- [Development](Development.md)
- [Refer](#Refer)

## Introduction

Laravel oriented package. This package is mainly for

1. CRUD generator for module
1. Create + manage multiple domain brand homepage + register to database for managing
1. Create component for main app or for brand + register to database for managing
1. Manage pages and navigations for brand
1. Console to import database searchable tables into elasticsearch
1. Create SQL for reporting + console to run SQL within the queue

## Requirements

1. A new Laravel related project (completedly new)
1. Composer require laravel/ui (no need installing the auth scaffolding)
1. Elastic Search
1. Redis or Memcached
1. Supervisord
1. A working NPM in your machine
1. Knowledge in jQuery, Bootstrap, Material Design Bootstrap, Axios, Sass, Lodash & all Laravel stuffs...

## Features

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
1. Global Search using ~Elastic Search (https://packagist.org/packages/elasticsearch/elasticsearch)~ Temporarily remove elasticsearch due to indexing issue. Looking into scout on elasticsearch driver..
1. Advanced filter prebuild text, date range and select
1. Socialite (support github, linkedin, google, facebook, twitter)
1. File Manager (https://github.com/UniSharp/laravel-filemanager)
1. Generate Brand Site with Subdomain
1. Component Management with try it online
1. Scout on elasticsearch driver (https://github.com/matchish/laravel-scout-elasticsearch)
1. Create page as your blog page
1. Personal Access Token
1. Report (BI?) - simple as fill the sql statements and save into table
1. Prerun reporting and store in cache
1. Prerun reporting "in queue" and store in cache
1. Excel (https://github.com/rap2hpoutre/fast-excel)
1. Create Service Facade as supporting Library
1. Stopping certain artisan cli perform on certain environments
1. Page can choose different template (but template must be pre-coded)
1. Locale for page module
1. Locale for navigation menu
1. Navigation Management

## Todo List

1. Tie user to brand
1. Carousel component for brand
1. Brand middleware and auth
1. Alias Domain for Brand
1. SEO Manager (https://github.com/lionix-team/seo-manager)
1. Queue Manager (Laravel Horizon is cool, but...)
1. Db connection for specific brand can be segregated by different connections. So we don't have to duplicate directory for each host.
1. Easily integrated with internal microservice or external microservice by lumen or any framework.

## Refer

1. https://developer.snapappointments.com/
1. https://sweetalert2.github.io/
1. https://unsplash.com/
1. https://lodash.com/
1. https://github.com/axios/axios
1. https://github.com/tighten/ziggy
1. https://gijgo.com/
1. http://www.daterangepicker.com/
1. https://summernote.org/
1. https://codemirror.net/
1. https://momentjs.com/
1. https://mdbootstrap.com
