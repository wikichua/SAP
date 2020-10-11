## Available Helper

- [settings](#settings)
- [activity](#activity)
- [viewRenderer](#viewRenderer)
- [route_slug](#route_slug)
- [getBrandName](#getBrandName)
- [getDomain](#getDomain)
- [brand](#brand)

#### settings

```php
settings($name, $default = '')
```

Return values from **Setting** Model.
Only set your value in **Setting** Module via the **Admin Panel**.

#### activity

```php
activity($message, $data = [], $model = null)
```

Log activity occurred.
This will viewable in **Activity Log** Module in **Admin Panel**.

#### viewRenderer

```php
viewRenderer($__php, $__data = [])
```

In case you want to render the **View Blade**.
This helper able to render the view seperately.

#### route_slug

```php
route_slug($name, string $slug, array $parameters = [], $locale ='')
```

Usually we are using **route** helper.
This helper allow only **slug** string and compute the complete url with append the **locale**,
So if your navigation content of different locale, you could just use this helper without purposely set the locale.
This helper will use locale that user choosed and appended with it.

#### getBrandName

```php
getBrandName($domain = '')
```

If in case you will need to get your brand name for whatever reason, you may pass the domain that currently on

```php
$domain = request()->getHost();
```

#### getDomain

```php
getDomain($name)
```

If in case you will need to get your primary & aliases domains, simply pass the brand name.

#### brand

```php
brand($brandName)
```

Did you know about auth helper?

```php
auth()
```

This **brand** helper is getting the brand model and cached to reduce multiple calls to the database.
