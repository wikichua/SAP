<?php

namespace Wikichua\SAP\View\Components;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;

class AdminMenu extends Component
{
    public $menu;
    public $activePatterns;
    public $groupActive;
    public $brandMenus;

    public function __construct($menu = '', $activePatterns = [])
    {
        $this->menu = $menu;
        $this->activePatterns = $activePatterns;
        $this->brandMenus = [];
    }

    public function render()
    {
        $dirs = File::directories(base_path('brand'));
        foreach ($dirs as $dir) {
            $config = [];
            if (File::exists($dir.'/config/main.php')) {
                $config = require($dir.'/config/main.php');
                if (File::exists($config['admin_path'].'/menu.blade.php')) {
                    View::addNamespace(basename($dir), $config['admin_path']);
                    $this->brandMenus[] = basename($dir).'::menu';
                }
            }
        }
        return view('sap::components.admin-menu');
    }
}
