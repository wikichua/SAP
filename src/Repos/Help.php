<?php
namespace Wikichua\SAP\Repos;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Queue;

class Help
{
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
        $sap_models = $this->getModels(base_path('vendor/wikichua/sap/src/Models'), config('sap.model_namespace'));
        if (($key = array_search('\Wikichua\SAP\Models\User', $sap_models)) !== false) {
            unset($sap_models[$key]);
        }
        $app_models = $this->getModels(app_path(), config('sap.custom_model_namespace')) + $this->getModels(app_path('Models'), config('sap.custom_model_namespace'));

        return array_merge($sap_models, $app_models, $this->getBrandsModelsList());
    }

    public function getBrandsModelsList()
    {
        $models = [];
        $dirs = File::directories(base_path('brand'));
        foreach ($dirs as $dir) {
            if (File::isDirectory($dir.'/Models')) {
                $namespace = str_replace(['brand','/'], ['Brand','\\'], (str_replace(base_path(), '', $dir.'/Models')));
                $models += $this->getModels($dir.'/Models', $namespace);
            }
        }
        return $models;
    }

    public function opendns()
    {
        return trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
    }

    public function iplocation($ip = '')
    {
        if ($ip == '') {
            $ip = $this->opendns();
        }
        return Cache::remember('iplocation:'.$ip, (60 * 60 * 24), function () use ($ip) {
            $fields = [
                'status','message','continent','continentCode','country','countryCode','region','regionName','city','district','zip','lat','lon','timezone','offset','currency','isp','org','as','asname','reverse','mobile','proxy','hosting','query'
            ];
            return json_decode(\Http::get('//ip-api.com/json/'.$ip, ['fields' => implode(',', $fields)]), 1);
        });
    }

    public function agent()
    {
        return (new \Jenssegers\Agent\Agent);
    }

    public function agents($key = '')
    {
        $agent = $this->agent();
        $data = [
            'languages' => $agent->languages(),
            'device' => $agent->device(),
            'platform' => $agent->platform(),
            'platform_version' => $agent->version($agent->platform()),
            'browser' => $agent->browser(),
            'browser_version' => $agent->version($agent->browser()),
            'isDesktop' => $agent->isDesktop(),
            'isPhone' => $agent->isPhone(),
            'isRobot' => $agent->isRobot(),
            'headers' => request()->headers->all(),
            'ips' => request()->ips(),
        ];
        if ($key != '' && isset($data[$key])) {
            return $data[$key];
        }
        return $data;
    }

    public function activity($message, $data = [], $model = null, $ip = '')
    {
        // unset hidden form fields
        foreach (['_token', '_method', '_submit'] as $unset_key) {
            if (isset($data[$unset_key])) {
                unset($data[$unset_key]);
            }
        }
        if ($ip == '') {
            $ip = $this->opendns();
        }

        app(config('sap.models.activity_log'))->create([
            'user_id' => auth()->check() ? auth()->user()->id : 1,
            'model_id' => $model ? $model->id : null,
            'model_class' => $model ? get_class($model) : null,
            'message' => $message,
            'data' => $data ? $data : null,
            'brand_id' => auth()->check() ? auth()->user()->brand_id : null,
            'opendns' => $ip,
            'agents' => $this->agents(),
            'iplocation' => $this->iplocation($ip),
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

    public function pushered($data, $channel = '', $event = 'general')
    {
        $actual_data = [];
        if (is_object($data)) {
            return false;
        }
        if (!is_array($data)) {
            if (json_decode($data)) {
                $data = json_decode($data, 1);
            } else {
                $actual_data['message'] = trim($data);
            }
        }
        $actual_data['sender_id'] = sha1(
            isset($data['sender_id'])? $data['sender_id']:(
                auth()->check()? auth()->id():0
            )
        );
        if (is_array($data)) {
            if (isset($data['message'])) {
                $actual_data = array_merge($actual_data, $data);
            } else {
                $actual_data['message'] = implode("<br />", $data);
            }
        }

        $config = config('broadcasting.connections.pusher');
        $pusher = new \Pusher\Pusher(
            $config['key'],
            $config['secret'],
            $config['app_id'],
            $config['options'],
        );
        return $pusher->trigger((sha1($channel != '' ? $channel : env("APP_NAME"))), sha1($event), $actual_data);
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

    public function cronjob_frequencies()
    {
        return [
            'everyMinute' => 'Every Minute',
            'everyTwoMinutes' => 'Every Two Minutes',
            'everyThreeMinutes' => 'Every Three Minutes',
            'everyFourMinutes' => 'Every Four Minutes',
            'everyFiveMinutes' => 'Every Five Minutes',
            'everyTenMinutes' => 'Every Ten Minutes',
            'everyFifteenMinutes' => 'Every Fifteen Minutes',
            'everyThirtyMinutes' => 'Every Thirty Minutes',
            'everyTwoHours' => 'Every Two Hours',
            'everyThreeHours' => 'Every Three Hours',
            'everyFourHours' => 'Every Four Hours',
            'everySixHours' => 'Every Six Hours',
            'hourly' => 'Hourly',
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'yearly' => 'Yearly',
        ];
    }

    public function slug_route($name, string $slug = '', array $parameters = [], $locale = '', $absolute = true)
    {
        if ($locale == '') {
            $locale = app()->getLocale() != ''? app()->getLocale():config('app.locale');
        }
        return route($name, array_merge([$locale,$slug], $parameters), $absolute);
    }

    public function getBrandName($domain = '')
    {
        $configs =  Cache::remember('brand-configs', (60*60*24), function () {
            $configs = [];
            $dirs = File::directories(base_path('brand'));
            foreach ($dirs as $dir) {
                $brand = basename($dir);
                $config = require($dir.'/config/domains.php');
                $configs[$config['main']] = $brand;
                foreach ($config['aliases'] as $alias) {
                    $configs[$alias] = $brand;
                }
            }
            return $configs;
        });
        return $domain == ''? $configs:$configs[$domain];
    }

    public function getDomain()
    {
        $domains = $this->getBrandName();
        return isset($domains[request()->getHost()])? request()->getHost():'null';
    }

    public function brand($brandName = '')
    {
        $brandName = $brandName != ''? $brandName:$this->getBrandName(request()->getHost());
        return Cache::remember('brand-'.$brandName, (60*60*24), function () use ($brandName) {
            return app(config('sap.models.brand'))->query()->whereStatus('A')->whereName($brandName)->where('published_at', '<', date('Y-m-d 23:59:59'))->where('expired_at', '>', date('Y-m-d 23:59:59'))->first();
        });
    }

    public function queue_keys($driver = 'redis')
    {
        $keys = [];
        if ($driver == 'redis') {
            $keys = Queue::getRedis()->keys('*');
            $queues = [];
            foreach ($keys as $i => $key) {
                $keys[$i] = str_replace([config('database.redis.options.prefix').'queues:'], '', $key);
            }
        }
        return $keys;
    }
}
