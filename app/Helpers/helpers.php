<?php

if (!function_exists('getAbilitiesString')) {
    function getAbilitiesString($key)
    {
        return collect(config("constants.abilities.$key"))->implode(',');
    }
}
