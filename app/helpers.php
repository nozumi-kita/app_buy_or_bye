<?php

use Carbon\CarbonInterface;
use Illuminate\Support\Number;

if (! function_exists('format_jst')) {
    function format_jst(CarbonInterface $date, string $format = 'Y/m/d H:i'): string
    {
        return $date->copy()->timezone(config('app.display_timezone'))->format($format);
    }
}

if (! function_exists('format_jpy')) {
    function format_jpy(int $price)
    {
        return Number::currency($price, in: 'JPY');
    }
}
