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
    function slug_route($name, array $parameters = [], $absolute = true)
    {
        return Help::slug_route($name, $parameters, $absolute);
    }
}
