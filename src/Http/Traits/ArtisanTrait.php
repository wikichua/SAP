<?php

namespace Wikichua\SAP\Http\Traits;

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
}
