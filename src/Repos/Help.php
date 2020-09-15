<?php
namespace Wikichua\SAP\Repos;

class Help
{
    public function dump($value='Hello Help')
    {
        dd($value);
    }

    public function qs_url($path = null, $qs = array(), $secure = null)
    {
        $url = app('url')->to($path, $secure);
        if (count($qs)) {
            foreach ($qs as $key => $value) {
                $qs[$key] = sprintf('%s=%s', $key, urlencode($value));
            }
            $url = sprintf('%s?%s', $url, implode('&', $qs));
        }
        return $url;
    }

    public function prettyPrintJson($value = '')
    {
        return stripcslashes(json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function settings($name, $default = '')
    {
        if (!is_array(config('settings.' . $name)) && json_decode(config('settings.' . $name), 1)) {
            return json_decode(config('settings.' . $name), 1) ? json_decode(config('settings.' . $name), 1) : $default;
        }
        return config('settings.' . $name, $default);
    }

    public function rebuildUrl($url, $params = [])
    {
        if (count($params)) {
            $parsedUrl = parse_url($url);
            if ($parsedUrl['path'] == null) {
                $url .= '/';
            }
            $separator = ($parsedUrl['query'] == null) ? '?' : '&';
            return $url .= $separator . http_build_query($params);
        }
        return $url;
    }

    public function findHashTag($string)
    {
        preg_match_all("/#(\\w+)/", $string, $matches);
        return $matches[1];
    }

    public function getModels($path, $namespace)
    {
        $out = [];
        $iterator = new \RecursiveDirectoryIterator(
            $path
        );
        foreach ($iterator as $item) {
            if ($item->isReadable() && $item->isFile() && mb_strtolower($item->getExtension()) === 'php') {
                // $out[] =  $namespace . str_replace("/", "\\", mb_substr($item->getRealPath(), mb_strlen($path), -4));
                $out[] =  $namespace .'\\'. basename(str_replace('.'.$item->getExtension(), '', $item->getRealPath()));
            }
        }
        return $out;
    }

    public function getModelsList()
    {
        // return \Cache::remember('getModelsList', (60*60*24), function () {
        $sap_models = getModels(base_path('vendor/wikichua/sap/src/Models'), config('sap.model_namespace'));
        if (($key = array_search('\Wikichua\SAP\Models\User', $sap_models)) !== false) {
            unset($sap_models[$key]);
        }
        $app_models = getModels(app_path(), config('sap.custom_model_namespace'));
        return array_merge($sap_models, $app_models);
        // });
    }


    public function activity($message, $data = [], $model = null)
    {
        // unset hidden form fields
        foreach (['_token', '_method', '_submit'] as $unset_key) {
            if (isset($data[$unset_key])) {
                unset($data[$unset_key]);
            }
        }

        // create model
        app(config('sap.models.activity_log'))->create([
            'user_id' => auth()->check() ? auth()->user()->id : null,
            'model_id' => $model ? $model->id : null,
            'model_class' => $model ? get_class($model) : null,
            'message' => $message,
            'data' => $data ? $data : null,
        ]);
    }

    public function scan_langs_dir()
    {
        $locales = [];
        $iterator = new DirectoryIterator(resource_path('lang'));
        foreach ($iterator as $fileinfo) {
            if (!$fileinfo->isDot()) {
                $locales[] = $fileinfo->getFilename();
            }
        }
        return $locales;
    }

    public function pushered($data = [], $channel = '', $event = 'general')
    {
        $pusher = new \Pusher\Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true
            ]
        );
        $pusher->trigger((sha1($channel != '' ? $channel : env("APP_NAME"))), sha1($event), $data);
    }

    public function isMenuActive($patterns = [])
    {
        return preg_match('/'.(implode('|', $patterns)).'/', request()->route()->getName())? 'active':'';
    }

    public function viewRenderer($__php, $__data = [])
    {
        $__php = \Blade::compileString($__php);
        $__data['__env'] = app(\Illuminate\View\Factory::class);
        $obLevel = ob_get_level();
        ob_start();
        extract($__data, EXTR_SKIP);
        try {
            eval('?' . '>' . $__php);
        } catch (Exception $e) {
            while (ob_get_level() > $obLevel) {
                ob_end_clean();
            }
            throw $e;
        } catch (Throwable $e) {
            while (ob_get_level() > $obLevel) {
                ob_end_clean();
            }
            throw new \Symfony\Component\Debug\Exception\FatalThrowableError($e);
        }
        return ob_get_clean();
    }

    public function timezones()
    {
        return array_combine(timezone_identifiers_list(), timezone_identifiers_list());
    }
}
