<?php

declare(strict_types=1);

namespace App\Helpers;

use Exception;
use App\Helpers\Message;
use App\Exceptions\ApiException;

class Validator
{
    public static function getAdditionalDataStructure($array)
    {
        $result = [];

        foreach ($array as $group) {
            foreach ($group['keys'] as $elem) {
                $result += [$elem => $group['type']];
            }
        }
        

        return $result;
    }

    public static function getRules($array)
    {
        $result = [];
        foreach ($array as $elem) {
            $result += ['additional_data.'.$elem => 'required'];
        }

        return $result;
    }

    public static function getMessages($array)
    {
        $result = [];
        foreach ($array as $elem) {
            $result += ['additional_data.'.$elem.'.required' => Message::addIsRequired(translate(strtoupper($elem)))];
        }

        return $result;
    }
}
