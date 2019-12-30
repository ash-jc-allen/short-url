<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Custom Routing
    |--------------------------------------------------------------------------
    |
    | If custom routing isn't enabled, the short URLs can be
    | navigated to with the route: /short/{shortURLKey}.
    | If custom routing is enabled, this route is
    | blocked in the controller so that you can
    | define your own custom route. Read the
    | docs for more information.
    |
    |
    */
    'custom_routing_enabled' => false,

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
    'key_length' => 5,

    /*
    |--------------------------------------------------------------------------
    | Tracking
    |--------------------------------------------------------------------------
    |
    | Define which fields are recorded if a shortened URL has
    | tracking enabled. Also define whether if tracking
    | is enabled by default.
    |
    */
    'tracking'   => [
        'default_enabled' => true,

        'fields' => [
            'ip_address'               => true,
            'operating_system'         => true,
            'operating_system_version' => true,
            'browser'                  => true,
            'browser_version'          => true,
        ],
    ],
];
