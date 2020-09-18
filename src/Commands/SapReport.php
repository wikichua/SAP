<?php

namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class SapReport extends Command
{
    protected $signature = 'sap:report';
    protected $description = 'Generating Report';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $reports = app(config('sap.models.report'))->query()->where('status', 'A')->get();
        foreach ($reports as $report) {
            if (Cache::get('report-'.str_slug($report->name)) == null) {
                dispatch(function () use ($report) {
                    cache()->remember(
                        'report-'.str_slug($report->name),
                        $report->cache_ttl,
                        function () use ($report) {
                            $results = [];
                            foreach ($report->queries as $sql) {
                                $results[] = array_map(function ($value) {
                                    return (array)$value;
                                }, \DB::select($sql));
                            }
                            return $results;
                        }
                    );
                    $report->generated_at = \Carbon\Carbon::now();
                    $report->next_generate_at = \Carbon\Carbon::now()->addSeconds($report->cache_ttl);
                    $report->save();
                })->onQueue('report_processing');
            }
        }
    }
}
