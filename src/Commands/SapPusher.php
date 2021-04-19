<?php

namespace Wikichua\SAP\Commands;

use Illuminate\Console\Command;

class SapPusher extends Command
{
    protected $signature = 'sap:pusher {--brand=}';
    protected $description = 'Generating Report';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = \Carbon\Carbon::now();
        $pushers = app(config('sap.models.pusher'))->query()->where('status', 'A')->whereBetween('scheduled_at', [$now, $now->addMinute()]);
        $brand = $this->option('brand');
        if ('' != $brand) {
            $brand = app(config('sap.models.brand'))->query()->where('name', $brand)->where('status', 'A')->first();
            if ($brand) {
                $pushers->where('brand_id', $brand->id);
            } else {
                $this->error($brand.' does not activated or not existed!');

                return;
            }
        } else {
            $pushers->whereNull('brand_id');
        }
        $pushers = $pushers->get();
        foreach ($pushers as $pusher) {
            $channel = '';
            if ($brand) {
                $channel = strtolower($brand->name);
            }
            pushered($pusher->toArray(), $channel, $pusher->event, $pusher->locale);
            $pusher->status = 'S';
            $pusher->save();
        }
        $this->line('Process Completed');
    }
}
