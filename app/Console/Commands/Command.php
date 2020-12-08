<?php

namespace App\Console\Commands;

use Illuminate\Console\Command as BaseCommand;

abstract class Command extends BaseCommand
{
    /**
     * Test if the string is valid json.
     *
     * @param string $string
     *
     * @return bool
     */
    protected function isJson($string)
    {
        json_decode($string);

        return json_last_error() == JSON_ERROR_NONE;
    }
}
