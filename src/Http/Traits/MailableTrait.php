<?php

namespace Wikichua\SAP\Http\Traits;

trait MailableTrait
{
    public function preview()
    {
        $vars = $this->getVariables();
        foreach (request()->input() as $key => $value) {
            if (in_array($key, $vars)) {
                $this->{$key} = $value;
            }
        }

        return $this;
    }
}
