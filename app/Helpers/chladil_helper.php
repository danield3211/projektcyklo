<?php

if (! function_exists('now')) {

    function flagSpan(string $country): string
    {
        if (!$country) return '';
        return '<span class="fi fi-' . strtolower(esc($country)) . '" title="' . strtoupper(esc($country)) . '"></span>';
    }

}