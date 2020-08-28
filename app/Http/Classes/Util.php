<?php

namespace App\Http\Classes;

class Util
{
    public static function explode($separador, $string)
    {
        if (strpos($string, $separador))
        {
            return explode($separador, $string);
        } else {
            return [$string];
        }
    }
}
