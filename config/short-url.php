<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Custom Prefix
    |--------------------------------------------------------------------------
    |
    | This configuration value is used to determine the prefix that
    | is registered for the short URL route.
    |
    */
    'prefix' => '/short',

    /*
    |--------------------------------------------------------------------------
    | Custom Database Connection
    |--------------------------------------------------------------------------
    |
    | This configuration value is used to override the database connection
    | that will be used by models of this package. If set to `null`, your
    | application's default database connection will be used.
    |
    */
    'connection' => null,

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | Define any middleware that the default short URL route will use.
    |
    */
    'middleware' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Eloquent Factories
    |--------------------------------------------------------------------------
    |
    | Define eloquent factories that you will use for your testing purposes.
    |
    */
    'factories' => [
        \AshAllenDesign\ShortURL\Models\ShortURL::class => \AshAllenDesign\ShortURL\Models\Factories\ShortURLFactory::class,
        \AshAllenDesign\ShortURL\Models\ShortURLVisit::class => \AshAllenDesign\ShortURL\Models\Factories\ShortURLVisitFactory::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Routing
    |--------------------------------------------------------------------------
    |
    | If the default route is disabled, this prevents any visitors
    | from using the default route that comes with this library.
    | This allows you to define your own route that points to
    | the controller at:
    |
    | AshAllenDesign\ShortURL\Controllers\ShortURLController
    |
    */
    'disable_default_route' => false,

    /*
    |--------------------------------------------------------------------------
    | Default URL
    |--------------------------------------------------------------------------
    |
    | Here you can override the default application base URL used to generate
    | the default short URL (default_short_url). To use your application's
    | "app.url" config value, set this field to null.
    |
    */
    'default_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Forwards query parameters
    |--------------------------------------------------------------------------
    |
    | Here you can specify if the newly created short URLs will forward
    | the query parameters to the destination by default. This option
    | can be overridden when creating the short URL with the
    | ->forwardQueryParams() method.
    |
    | eg: https://yoursite.com/short/xxx?a=b => https://destination.com/page?a=b
    |
    */
    'forward_query_params' => false,

    /*
    |--------------------------------------------------------------------------
    | Enforce HTTPS in the destination URL
    |--------------------------------------------------------------------------
    |
    | Here you may specify if the visitor is redirected to the HTTPS
    | version of the destination URL by default. This option can be
    | overridden when creating the short URL with the ->secure()
    | method.
    |
    */
    'enforce_https' => true,

    /*
    |--------------------------------------------------------------------------
    | Allowed URL Schemes
    |--------------------------------------------------------------------------
    |
    | Here you may specify the allowed URL schemes to shorten. For example:
    | 'mailto://', 'whatsapp://', 'yourapp://'.
    |
    */
    'allowed_url_schemes' => [
        'http://',
        'https://',
    ],

    /*
    |--------------------------------------------------------------------------
    | URL Length
    |--------------------------------------------------------------------------
    |
    | The character length of the shortened URL. The 'key_length' must be
    | at least 3. However, for performance reasons, it is recommended to
    | not use a 'key_length' lower than 5.
    |
    | e.g. - Using a length of 3 would result in yourdomain.com/XXX
    |      - Using a length of 5 would result in yourdomain.com/XXXXX
    |
    | Note: This is the desired length and will act as a minimum
    |       length, not as a fixed length. For example, if all
    |       of the possible 3 character-length keys have been
    |       used, a 4 character long key will be created.
    |
    */
    'key_length' => 5,

    /*
    |--------------------------------------------------------------------------
    | Key Salt
    |--------------------------------------------------------------------------
    |
    | Define the salt that is used to create the unique short
    | URL keys. This is used to ensure that the randomly
    | generated keys are unique.
    |
    */
    'key_salt' => 'AshAllenDesign\ShortURL',

    /*
    |--------------------------------------------------------------------------
    | Alphabet
    |--------------------------------------------------------------------------
    |
    | Define the characters allowed in the output short URL keys.
    | The 'alphabet' must be at least 16 unique characters
    | and cannot contain spaces.
    |
    */
    'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',

    /*
    |--------------------------------------------------------------------------
    | Tracking
    |--------------------------------------------------------------------------
    |
    | Define which fields are recorded if a shortened URL has
    | tracking enabled. Also define whether if tracking
    | is enabled by default. Each of these options can
    | be overridden when creating a short URL.
    |
    */
    'tracking' => [
        'default_enabled' => true,

        'fields' => [
            'ip_address' => true,
            'operating_system' => true,
            'operating_system_version' => true,
            'browser' => true,
            'browser_version' => true,
            'referer_url' => true,
            'device_type' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Config Validation
    |--------------------------------------------------------------------------
    |
    | Choose whether if you want the config to be validated. This
    | can be useful for ensuring that your config values are
    | safe to use.
    |
    */
    'validate_config' => false,

    /*
    |--------------------------------------------------------------------------
    | User Agent Parser
    |--------------------------------------------------------------------------
    |
    | Define the class that should be used to handle the parsing of the user
    | agent string. This class must implement the following interface:
    | AshAllenDesign\ShortURL\Interfaces\UserAgentDriver.
    |
    */
    'user_agent_driver' => \AshAllenDesign\ShortURL\Classes\UserAgent\ParserPhpDriver::class,

    /*
    |--------------------------------------------------------------------------
    | Short URL Key Generator Class
    |--------------------------------------------------------------------------
    |
    | Define the class that should be used to handle the creation of a unique
    | short URL key. This class must implement the following interface:
    | AshAllenDesign\ShortURL\Interfaces\UrlKeyGenerator.
    |
    */
    'url_key_generator' => \AshAllenDesign\ShortURL\Classes\KeyGenerator::class,
];
