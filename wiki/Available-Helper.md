## Available Helper

- [timezones](#timezones)
- [settings](#settings)
- [activity](#activity)
- [queue_keys](#queue_keys)
- [viewRenderer](#viewRenderer)
- [renderSlug](#renderSlug)
- [route_slug](#route_slug)
- [getBrandName](#getBrandName)
- [getBrand](#getBrand)
- [getDomain](#getDomain)
- [brand](#brand)
- [opendns](#opendns)
- [iplocation](#iplocation)
- [agent and agents](#agent-and-agents)
- [pushered](#pushered)

### <a name="timezones"></a>timezones

Listed timezones available

```php
timezones()
```

### <a name="settings"></a>settings

```php
settings($name, $default = '')
```

Return values from **Setting** Model.
Only set your value in **Setting** Module via the **Admin Panel**.

### <a name="activity"></a>activity

```php
activity($message, $data = [], $model = null, $ip = '')
```

Log activity occurred.
This will viewable in **Activity Log** Module in **Admin Panel**.

### <a name="queue_keys"></a>queue_keys

Listed all keys in pending withing the queue (temporarily only redis supported)

```php
queue_keys($driver = 'redis')
```

### <a name="viewRenderer"></a>viewRenderer

```php
viewRenderer($__php, $__data = [])
```

In case you want to render the **View Blade**.
This helper able to render the view seperately.


### <a name="renderSlug"></a>renderSlug

```php
renderSlug($slug, $locale = '')
```

Render the slug passed in directly

### <a name="route_slug"></a>route_slug

```php
route_slug($name, string $slug, array $parameters = [], $locale ='')
```

Usually we are using **route** helper.
This helper allow only **slug** string and compute the complete url with append the **locale**,
So if your navigation content of different locale, you could just use this helper without purposely set the locale.
This helper will use locale that user choosed and appended with it.

### <a name="getBrandName"></a>getBrandName

```php
getBrandName($domain = '')
```

If in case you will need to get your brand name for whatever reason, you may pass the domain that currently on

### <a name="getBrand"></a>getBrand

```php
getBrand($brandName)
```

If in case you will need to get your current brand for a reason.

```php
$domain = request()->getHost();
```

### <a name="getDomain"></a>getDomain

```php
getDomain($name)
```

If in case you will need to get your primary & aliases domains, simply pass the brand name.

### <a name="brand"></a>brand

```php
brand($brandName)
```

Did you know about auth helper?

```php
auth()
```

This **brand** helper is getting the brand model and cached to reduce multiple calls to the database.

### <a name="agent-and-agents"></a>agent and agents

Return instance from [jenssegers/agent](https://github.com/jenssegers/agent)

```php
agent()
```

```php
agents($key)
```

key = null to return all
available keys:
headers, ips, opendns, iplocation, languages, device, platform, platform_version, browser, browser_version, isDesktop, isPhone, isRobot

### <a name="opendns"></a>opendns

Return open IP

```php
opendns()
```

### <a name="iplocation"></a>iplocation

Return open IP Location

```php
iplocation($ip = '')
```

### <a name="pushered"></a>pushered

```php
pushered($data, $channel = '', $event = 'general', $locale = 'en', $driver = '');
```

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

By default, this will pushed to "general" event on your default app channel.

- **message** key is important as to show on the web push notification. If there is no message key defined, array of the param will be imploded to string and assigned to message key.
