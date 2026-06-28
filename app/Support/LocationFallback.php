<?php

namespace App\Support;

class LocationFallback
{
    public static function get($ip = null)
    {
        return null;
    }

    public static function current()
    {
        return null;
    }
}
