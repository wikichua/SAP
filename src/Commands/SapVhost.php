<?php

namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;

class SapVhost extends Command
{
    protected $signature = 'sap:vhost {action} {domain?}';
    protected $description = 'Manage Virtual Host (Linux)';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $action = strtolower(trim($this->argument('action')));
        if (in_array($action, ['create','delete'])) {
            $domain = trim($this->argument('domain'));
            $path = public_path();
            $vhostsh = base_path('vendor/wikichua/sap/stubs/virtualhost-nginx');
            $result = shell_exec("sudo ". $vhostsh.' '.$action.' '.$domain.' '.$path);
            $this->line($result);
        } else {
            $hosts = explode(PHP_EOL,shell_exec("cat /etc/hosts"));
            $results = [];
            foreach ($hosts as $host) {
                if (str_contains($host, '127.0.0.1')) {
                    $results[] = explode("\t", $host);
                }
            }
            $this->table(['IP', 'DNS'], $results);
        }
    }
}
