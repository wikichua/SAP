<?php

namespace Wikichua\SAP\Http\Traits;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Stringable;

trait ArtisanTrait
{
    public function disable(array $commands = [], array $envs = ['production'])
    {
        if (in_array(app()->environment(), $envs)) {
            foreach ($commands as $command) {
                \Artisan::command($command, function () {
                    $this->comment('You are not allowed to do this in production!');
                })->describe('Override default command in production.');
            }
        }
    }

    public function runCronjobs(Schedule $schedule)
    {
        $cronjobs = cache()->tags('cronjob')->rememberForever('cronjobs', function () {
            return app(config('sap.models.cronjob'))->whereStatus('A')->get();
        });
        foreach ($cronjobs as $cronjob) {
            $frequency = $cronjob->frequency;
            $cron = app(config('sap.models.cronjob'))->find($cronjob->id);
            $time = Carbon::now()->timezone($cron->timezone)->toDateTimeString();
            $outputed = is_array($cron->output) ? $cron->output : [];
            if ('art' == $cronjob->mode) {
                $schedule->command($cronjob->command)->{$frequency}()
                    ->timezone($cronjob->timezone)
                    ->onSuccess(function (Stringable $output) use ($cron, $time, $outputed) {
                        $cron->output = array_merge([$time => $output], $outputed);
                        $cron->save();
                    })
                    ->onFailure(function (Stringable $output) use ($cron, $time, $outputed) {
                        $cron->output = array_merge([$time => $output], $outputed);
                        $cron->save();
                    });
            } else {
                $schedule->exec($cronjob->command);
            }
        }
    }
}
