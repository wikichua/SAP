# SAP - Simple Admin Panel

- [Introduction](#Introduction)
- [Requirements](#Requirements)
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
1. Pusher Manager - Web Push Notification, Real Time Messenger
1. Alert Notification - Not fancy but simple notification like facebook
1. Snapshot Table Row - Able to let Admin to restore the changed data, or deleted data

## <a name="Requirements"></a>Requirements

1. A new Laravel related project (completedly new)
1. Composer require laravel/ui (no need installing the auth scaffolding)
1. Redis
1. Supervisord
1. A working NPM in your machine
1. Knowledge in jQuery, Bootstrap, Material Design Bootstrap, Axios, Sass, Lodash & all Laravel stuffs...

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
1. https://github.com/diglactic/laravel-breadcrumbs
