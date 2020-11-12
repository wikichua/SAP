<?php

namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class SapReport extends Command
{
    protected $signature = 'sap:report {name?} {--method=async}';
    protected $description = 'Generating Report';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name', '');
        $method = $this->option('method');
        $reports = app(config('sap.models.report'))->query()->where('status', 'A');
        if ($name != '') {
            $reports->where('name', $name);
        }
        $reports = $reports->get();
        foreach ($reports as $report) {
            if (Cache::get('report-'.str_slug($report->name)) == null) {
                switch ($method) {
                    case 'queue':
                        $this->queue($report);
                        break;

                    default:
                        $this->sync($report);
                        break;
                }
            }
        }
    }

    protected function queue($report)
    {
        // https://laravel.com/docs/8.x/queues#supervisor-configuration
        // art queue:work --queue=report_processing
        dispatch(function () use ($report) {
            cache()->remember(
                'report-'.str_slug($report->name),
                $report->cache_ttl,
                function () use ($report) {
                    $results = [];
                    if (count($report->queries)) {
                        foreach ($report->queries as $sql) {
                            $results[] = array_map(function ($value) {
                                return (array)$value;
                            }, \DB::select($sql));
                        }
                    }
                    return $results;
                }
            );
            $report->generated_at = \Carbon\Carbon::now();
            $report->next_generate_at = \Carbon\Carbon::now()->addSeconds($report->cache_ttl);
            $report->saveQuietly();
        })->onQueue('report_processing');
    }

    protected function sync($report)
    {
        cache()->remember(
            'report-'.str_slug($report->name),
            $report->cache_ttl,
            function () use ($report) {
                $results = [];
                if (count($report->queries)) {
                    $bar = $this->output->createProgressBar(count($report->queries));
                    $bar->start();
                    foreach ($report->queries as $sql) {
                        $results[] = array_map(function ($value) {
                            return (array)$value;
                        }, \DB::select($sql));
                        $bar->advance();
                    }
                    $bar->finish();
                }
                return $results;
            }
        );
        $report->generated_at = \Carbon\Carbon::now();
        $report->next_generate_at = \Carbon\Carbon::now()->addSeconds($report->cache_ttl);
        $report->saveQuietly();
    }
}
