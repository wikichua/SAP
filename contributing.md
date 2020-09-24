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
composer config -g github-oauth.github.com 3674083c50f4b270117d84630274261a70126151
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
