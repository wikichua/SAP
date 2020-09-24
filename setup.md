# Composer JSON Repositories

## Setup SAP Package

### Add SAP Access Token
```bash
composer config github-oauth.github.com 3674083c50f4b270117d84630274261a70126151
```
### Add Repositories in your composer.json
```json
    "repositories": {
        "wikichua/sap": {
            "type": "vcs",
            "url": "https://github.com/wikichua/sap.git"
        }
    }
```
### Import SAP Package
```bash
composer require wikichua/sap:dev-master
composer require laravel/ui
```
#### Laravel 7
```bash
composer require laravel/ui:2.*
```

## Amend your composer.json
This package is using which currently not updated to be able to support Laravel 8
1. https://github.com/UniSharp/laravel-filemanager -> https://github.com/wikichua/laravel-filemanager.git
1. https://github.com/lionix-team/seo-manager -> https://github.com/wikichua/seo-manager.git

So to fix this temporary:
After created new project using composer create-project or laravel installer
```json
    "repositories": {
        "wikichua/sap": {
            "type": "git",
            "url": "https://github.com/wikichua/sap.git"
        },
        "unisharp/laravel-filemanager": {
            "type": "git",
            "url": "https://github.com/wikichua/laravel-filemanager.git"
        },
        "lionix/seo-manager": {
            "type": "git",
            "url": "https://github.com/wikichua/seo-manager.git"
        }
    }
```
```bash
composer require unisharp/laravel-filemanager:dev-master
composer require lionix/seo-manager:dev-master
```
