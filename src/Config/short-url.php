<?php

return [

    /*
    |--------------------------------------------------------------------------
    | URL Length
    |--------------------------------------------------------------------------
    |
    | The character length of the shortened URL.
    | e.g. - Using a length of 3 would result in yourdomain.com/XXX
    |      - Using a length of 5 would result in yourdomain.com/XXXXX
    |
    */
    'length'   => 5,

    /*
    |--------------------------------------------------------------------------
    | Tracking
    |--------------------------------------------------------------------------
    |
    | Define which parameters are recorded if a shortened URL has
    | tracking enabled.
    |
    */
    'tracking' => [
        'ip_address'               => true,
        'operating_system'         => true,
        'operating_system_version' => true,
        'browser'                  => true,
        'browser_version'          => true,
    ],
];