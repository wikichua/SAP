<?php
if (!function_exists('qs_url')) {
    function qs_url($path = null, $qs = array(), $secure = null)
    {
        return Help::qs_url($path, $qs, $secure);
    }
}
if (!function_exists('prettyPrintJson')) {
    function prettyPrintJson($value = '')
    {
        return Help::prettyPrintJson($value);
    }
}
if (!function_exists('settings')) {
    function settings($name, $default = '')
    {
        return Help::settings($name, $default);
    }
}
if (!function_exists('rebuildUrl')) {
    function rebuildUrl($url, $params = [])
    {
        return Help::rebuildUrl($url, $params);
    }
}
if (!function_exists('findHashTag')) {
    function findHashTag($string)
    {
        return Help::findHashTag($string);
    }
}
if (!function_exists('getModels')) {
    function getModels($path, $namespace)
    {
        return Help::getModels($path, $namespace);
    }
}
if (!function_exists('getModelsList')) {
    function getModelsList()
    {
        return Help::getModelsList();
    }
}
if (!function_exists('activity')) {
    function activity($message, $data = [], $model = null)
    {
        return Help::activity($message, $data, $model);
    }
}
if (!function_exists('agent')) {
    function agent()
    {
        return Help::agent();
    }
}
if (!function_exists('agents')) {
    function agents($key = '')
    {
        return Help::agents($key);
    }
}

if (!function_exists('scan_langs_dir')) {
    function scan_langs_dir()
    {
        return Help::scan_langs_dir();
    }
}

if (!function_exists('pushered')) {
    function pushered($data = [], $channel = '', $event = 'general')
    {
        return Help::pushered($data, $channel, $event);
    }
}
if (!function_exists('isMenuActive')) {
    function isMenuActive($patterns = [])
    {
        return Help::isMenuActive($patterns);
    }
}
if (!function_exists('viewRenderer')) {
    function viewRenderer($__php, $__data = [])
    {
        return Help::viewRenderer($__php, $__data);
    }
}
if (!function_exists('slug_route')) {
    function slug_route($name, string $slug = '', array $parameters = [], $locale = '', $absolute = true)
    {
        return Help::slug_route($name, $slug, $parameters, $locale, $absolute);
    }
}
if (!function_exists('route_slug')) {
    function route_slug($name, string $slug = '', array $parameters = [], $locale = '', $absolute = true)
    {
        return Help::slug_route($name, $slug, $parameters, $locale, $absolute);
    }
}
if (!function_exists('getBrandName')) {
    function getBrandName($domain = '')
    {
        return Help::getBrandName($domain);
    }
}
if (!function_exists('getDomain')) {
    function getDomain()
    {
        return Help::getDomain();
    }
}
if (!function_exists('brand')) {
    function brand($brandName)
    {
        return Help::brand($brandName);
    }
}
