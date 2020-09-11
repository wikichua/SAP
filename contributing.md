# Contributing

Contributions are welcome and will be fully credited.

Contributions are accepted via Pull Requests on [Github](https://github.com/wikichua/sap).

# Things you could do

Need packager to ease your work

> $ composer require jeroen-g/laravel-packager dev-master

Import package from github

> $ php artisan packager:git git@github.com:wikichua/sap.git

Add this in your composer.json under scripts section:

```bash
"require-dev": {
    "wikichua/sap": "*"
},
```
Run composer update

## Composer VSC style of import

```bash
composer config -g github-oauth.github.com 8d37e2ab2bbce7e977d9fa3a814efab21504243a
```
In your composer.json

```json
    "repositories": {
        "wikichua/sap": {
            "type": "vcs",
            "url": "https://github.com/wikichua/sap.git"
        }
    }
```

**Happy coding**!
