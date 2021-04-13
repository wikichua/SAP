<?php

namespace Wikichua\SAP\Http\Traits;

use Illuminate\Console\Scheduling\Schedule;

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
            $param = '';
            if ('everySeconds' == $frequency) {
                $frequency = 'cron';
                $param = '* * * * *';
            }
            if ('art' == $cronjob->mode) {
                $schedule->command($cronjob->command)->{$frequency}($param)->timezone($cronjob->timezone);
            } else {
                $schedule->exec($cronjob->command)->{$frequency}($param)->timezone($cronjob->timezone);
            }
        }
    }
}
