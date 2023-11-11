<p align="center">
<img src="https://ashallendesign.co.uk/images/custom/short-url-logo.png" width="400">
</p>

<p align="center">
<a href="https://packagist.org/packages/ashallendesign/short-url"><img src="https://img.shields.io/packagist/v/ashallendesign/short-url.svg?style=flat-square" alt="Latest Version on Packagist"></a>
<a href="https://packagist.org/packages/ashallendesign/short-url"><img src="https://img.shields.io/packagist/dt/ashallendesign/short-url.svg?style=flat-square" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/ashallendesign/short-url"><img src="https://img.shields.io/packagist/php-v/ashallendesign/short-url?style=flat-square" alt="PHP from Packagist"></a>
<a href="https://github.com/ash-jc-allen/short-url/blob/master/LICENSE"><img src="https://img.shields.io/github/license/ash-jc-allen/short-url?style=flat-square" alt="GitHub license"></a>
</p>

## Table of Contents

- [Overview](#overview)
- [Installation](#installation)
    - [Requirements](#requirements)
    - [Install the Package](#install-the-package)
    - [Publish the Config and Migrations](#publish-the-config-and-migrations)
    - [Migrate the Database](#migrate-the-database)
- [Usage](#usage)
    - [Building Shortened URLs](#building-shortened-urls)
        - [Quick Start](#quick-start)
        - [Custom Keys](#custom-keys)
        - [Tracking Visitors](#tracking-visitors)
            - [Enabling Tracking](#enabling-tracking)
            - [Tracking IP Address](#tracking-ip-address)
            - [Tracking Browser & Browser Version](#tracking-browser--browser-version)
            - [Tracking Operating System & Operating System Version](#tracking-operating-system--operating-system-version)
            - [Tracking Device Type](#tracking-device-type)
            - [Tracking Referer URL](#tracking-referer-url)
        - [Custom Short URL Fields](#custom-short-url-fields)
        - [Single Use](#single-use)
        - [Enforce HTTPS](#enforce-https)
        - [Forwarding Query Parameters](#forwarding-query-parameters)
        - [Redirect Status Code](#redirect-status-code)
        - [Activation and Deactivation Times](#activation-and-deactivation-times)
        - [Using a Custom Seed](#using-a-custom-seed)
        - [Facade](#facade)
        - [Conditionals](#conditionals)
    - [Using the Shortened URLs](#using-the-shortened-urls)
        - [Default Route and Controller](#default-route-and-controller)
        - [Custom Route](#custom-route)
    - [Tracking](#tracking)
    - [Customisation](#customisation)
        - [Disabling the Default Route](#disabling-the-default-route)
        - [Default URL Key Length](#default-url-key-length)
        - [Tracking Visits](#tracking-visits)
            - [Default Tracking](#default-tracking)
            - [Tracking Fields](#tracking-fields)
        - [Config Validation](#config-validation)
        - [Custom Database Connection](#custom-database-connection)
    - [Helper Methods](#helper-methods)
        - [Visits](#visits)
        - [Find by URL Key](#find-by-url-key)
        - [Find by Destination URL](#find-by-destination-url)
        - [Tracking Enabled](#tracking-enabled)
        - [Tracked Fields](#tracked-fields)
    - [Events](#events)
        - [Short URL Visited](#short-url-visited)
    - [Model Factories](#model-factories)
- [Testing](#testing)
- [Security](#security)
- [Contribution](#contribution)
- [Credits](#credits)
- [Changelog](#changelog)
- [Upgrading](#upgrading)
- [License](#license)
    
## Overview

A Laravel package that can be used for adding shortened URLs to your existing web app.

## Installation

### Requirements
The package has been developed and tested to work with the following minimum requirements:

- PHP 8.0
- Laravel 8.0

Short URL requires either the [BC Math](https://secure.php.net/manual/en/book.bc.php) or [GMP](https://secure.php.net/manual/en/book.gmp.php) PHP extensions in order to work.

### Install the Package
You can install the package via Composer:

```bash
composer require ashallendesign/short-url
```

### Publish the Config and Migrations
You can then publish the package's config file and database migrations by using the following command:
```bash
php artisan vendor:publish --provider="AshAllenDesign\ShortURL\Providers\ShortURLProvider"
```

### Migrate the Database
This package contains two migrations that add two new tables to the database: ``` short_urls ``` and ``` short_url_visits ```. To run these migrations, simply run the following command:
```bash
php artisan migrate
```

## Usage
### Building Shortened URLs
#### Quick Start
The quickest way to get started with creating a shortened URL is by using the snippet below. The ``` ->make() ``` method
 returns a ShortURL model that you can grab the shortened URL from.
```php
$builder = new \AshAllenDesign\ShortURL\Classes\Builder();

$shortURLObject = $builder->destinationUrl('https://destination.com')->make();
$shortURL = $shortURLObject->default_short_url;
```

#### Custom Keys
By default, the shortened URL that is generated will contain a random key. The key will be of the length that you define
in the config files (defaults to 5 characters). Example: if a URL is ``` https://webapp.com/short/abc123 ```, the key is
``` abc123 ```.

You may wish to define a custom key yourself for that URL that is more meaningful than a randomly generated one. You can
do this by using the ``` ->urlKey() ``` method. Example:

```php
$builder = new \AshAllenDesign\ShortURL\Classes\Builder();

$shortURLObject = $builder->destinationUrl('https://destination.com')->urlKey('custom-key')->make();
$shortURL = $shortURLObject->default_short_url;

// Short URL: https://webapp.com/short/custom-key
```

Note: All of the URL keys are unique, so you cannot use a key that already exists in the database for another shortened
URL.

#### Tracking Visitors
You may want to track some data about the visitors that have used the shortened URL. This can be useful for analytics.
By default, tracking is enabled and all of the available tracking fields are also enabled. You can toggle the default
options for the different parts of the tracking in the config file. Read further on in the [Customisation](#customisation)
section to see how to customise the default tracking behaviours.

Note: Even if the tracking options (such as ``` track_ip_address ```) are enabled for a short URL, they won't be recorded
unless the ``` track_visits ``` options is enabled. This can come in handy if you want to enable/disable tracking for a
short URL without needing to individually set each option.

##### Enabling Tracking

If you want to override whether if tracking is enabled or not when creating a shortened URL, you can use the ``` ->trackVisits() ``` method.
This method accepts a boolean but defaults to ``` true ``` if a parameter is not passed.

The example below shows how to enable tracking for the URL and override the config variable:
```php
$builder = new \AshAllenDesign\ShortURL\Classes\Builder();

$shortURLObject = $builder->destinationUrl('https://destination.com')->trackVisits()->make();
```

The example below shows how to disable tracking for the URL and override the default config variable:
```php
$builder = new \AshAllenDesign\ShortURL\Classes\Builder();

$shortURLObject = $builder->destinationUrl('https://destination.com')->trackVisits(false)->make();
```

##### Tracking IP Address

If you want to override whether if IP address tracking is enabled or not when creating a shortened URL, you can use the
``` ->trackIPAddress() ``` method. This method accepts a boolean but defaults to ``` true ``` if a parameter is not passed.

The example below shows how to enable IP address tracking for the URL and override the default config variable:
```php
$builder = new \AshAllenDesign\ShortURL\Classes\Builder();

$shortURLObject = $builder->destinationUrl('https://destination.com')->trackVisits()->trackIPAddress()->make();
```

##### Tracking Browser & Browser Version

If you want to override whether if browser name and browser version tracking is enabled or not when creating a shortened
URL, you can use the ``` ->trackBrowser() ``` and ``` ->trackBrowserVersion() ``` methods. This method accepts a boolean
but defaults to ``` true ``` if a parameter is not passed.

The example below shows how to enable browser name tracking for the URL and override the default config variable:
```php
$builder = new \AshAllenDesign\ShortURL\Classes\Builder();

$shortURLObject = $builder->destinationUrl('https://destination.com')->trackVisits()->trackBrowser()->make();
```

The example below shows how to enable browser version tracking for the URL and override the default config variable:
```php
$builder = new \AshAllenDesign\ShortURL\Classes\Builder();

$shortURLObject = $builder->destinationUrl('https://destination.com')->trackVisits()->trackBrowserVersion()->make();
```

##### Tracking Operating System & Operating System Version

If you want to override whether if operating system name and operating system version tracking is enabled or not when
creating a shortened URL, you can use the ``` ->trackOperatingSystem() ``` and ``` ->trackOperatingSystemVersion() ```
methods. These methods accept a boolean but default to ``` true ``` if a parameter is not passed.

The example below shows how to enable operating system name tracking for the URL and override the default config variable:
```php
$builder = new \AshAllenDesign\ShortURL\Classes\Builder();

$shortURLObject = $builder->destinationUrl('https://destination.com')->trackVisits()->trackOperatingSystem()->make();
```

The example below shows how to enable operating system version tracking for the URL and override the default config variable:
```php
$builder = new \AshAllenDesign\ShortURL\Classes\Builder();

$shortURLObject = $builder->destinationUrl('https://destination.com')->trackVisits()->trackOperatingSystemVersion()->make();
```

##### Tracking Device Type

If you want to override whether if device type tracking is enabled or not when creating a shortened URL, you can use the
``` ->trackDeviceType() ``` method. This method accepts a boolean but defaults to ``` true ``` if a parameter is not passed.

The example below shows how to enable device type tracking for the URL and override the default config variable:
```php
$builder = new \AshAllenDesign\ShortURL\Classes\Builder();

$shortURLObject = $builder->destinationUrl('https://destination.com')->trackVisits()->trackDeviceType()->make();
```

##### Tracking Referer URL

If you want to override whether if referer URL tracking is enabled or not when creating a shortened URL, you can use the
``` ->trackRefererURL() ``` method. This method accepts a boolean but defaults to ``` true ``` if a parameter is not passed.

The example below shows how to enable referer URL tracking for the URL and override the default config variable:
```php
$builder = new \AshAllenDesign\ShortURL\Classes\Builder();

$shortURLObject = $builder->destinationUrl('https://destination.com')->trackVisits()->trackRefererURL()->make();
```

#### Custom Short URL Fields

There may be times when you want to add your own custom fields to the ShortURL model and store them in the database. For example, you might want to associate the short URL with a tenant, organisation, user, etc.

To do this you can use the `beforeCreate` method when building your short URL. This method accepts a closure that receives the `AshAllenDesign\ShortURL\Models\ShortURL` model instance before it's saved to your database.

The example below shows how to add a `tenant_id` field to the `AshAllenDesign\ShortURL\Models\ShortURL` model:

```php
use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Facades\ShortURL as ShortUrlBuilder;

$tenantId = 123;

$shortURL = ShortUrlBuilder::destinationUrl($url)
    ->beforeCreate(function (ShortURL $model): void {
        $model->tenant_id = $tenantId;
    })
    ->make();
```

Please remember that to store custom fields in the database, you'll have to make sure those fields are added to the `short_urls` table. You can do this by creating a new migration that adds the fields to the table, or by updating the migrations that ship with this package.

#### Single Use
By default, all of the shortened URLs can be visited for as long as you leave them available. However, you may want to
only allow access to a shortened URL once. Then any visitors who visit the URL after it has already been viewed will
get a HTTP 404.

To create a single use shortened URL, you can use the ``` ->singleUse() ``` method.

The example below shows how to create a single use shortened URL:
 ```php
 $builder = new \AshAllenDesign\ShortURL\Classes\Builder();
 
 $shortURLObject = $builder->destinationUrl('https://destination.com')->singleUse()->make();
 ```

#### Enforce HTTPS
When building a shortened URL, you might want to enforce that the visitor is redirected to the HTTPS version
of the destination URL. This can be particularly useful if you're allowing your web app users to create their own shortened
URLS.

To enforce HTTPS, you can use the ``` ->secure() ``` method when building the shortened URL.

The example below shows how to create a secure shortened URL:
 ```php
$builder = new \AshAllenDesign\ShortURL\Classes\Builder();
 
$shortURLObject = $builder->destinationUrl('http://destination.com')->secure()->make();

// Destination URL: https://destination.com
 ```

#### Forwarding Query Parameters
When building a short URL, you might want to forward the query parameters sent in the request to destination URL. By default, this functionality is disabled, but can be enabled by setting the `forward_query_params` config option to `true`.

Alternatively, you can also use the `->forwardQueryParams()` method when building your shortened URL, as shown in the example below:

 ```php
$builder = new \AshAllenDesign\ShortURL\Classes\Builder();
 
$shortURLObject = $builder->destinationUrl('http://destination.com?param1=test')->forwardQueryParams()->make();
 ```

Based on the example above, assuming that the original short URL's `destination_url` was `https://destination.com`, making a request to `https://webapp.com/short/xxx?param1=abc&param2=def` would redirect to `https://destination.com?param1=test&param2=def`

#### Redirect Status Code

By default, all short URLs are redirected with a ``` 301 ``` HTTP status code. But, this can be overridden when building
the shortened URL using the ``` ->redirectStatusCode() ``` method.

The example below shows how to create a shortened URL with a redirect HTTP status code of ``` 302 ```:
 ```php
$builder = new \AshAllenDesign\ShortURL\Classes\Builder();
 
$shortURLObject = $builder->destinationUrl('http://destination.com')->redirectStatusCode(302)->make();
 ```

#### Activation and Deactivation Times

By default, all short URLs that you create are active until you delete them. However, you may set activation and deactivation
times for your URLs when you're creating them.

Doing this can be useful for marketing campaigns. For example, you may want to launch a new URL for a marketing campaign on
a given date and then automatically deactivate that URL when the marketing campaign comes to an end.

The example below shows how to create a shortened URL that will be active from this time tomorrow onwards:

 ```php
$builder = new \AshAllenDesign\ShortURL\Classes\Builder();
 
$shortURLObject = $builder->activateAt(\Carbon\Carbon::now()->addDay())->make();
 ```

The example below shows how to create a shortened URL that will be active from this time tomorrow onwards and then is
deactivated the day after:

 ```php
$builder = new \AshAllenDesign\ShortURL\Classes\Builder();
 
$shortURLObject = $builder->activateAt(\Carbon\Carbon::now()->addDay())
                           ->deactivateAt(\Carbon\Carbon::now()->addDays(2))
                           ->make();
 ```

#### Using a Custom Seed

By default, the package will use the ID of the last inserted short URL as the seed for generating a short URL's key. In some cases, you may want to use a custom seed instead. To do this, you can pass an integer to the `generateKeyUsing` method like so:

 ```php
$builder = new \AshAllenDesign\ShortURL\Classes\Builder();
 
$shortURLObject = $builder->destinationUrl('https://destination.com')
    ->generateKeyUsing(12345)
    ->make();
 ```

#### Facade
If you prefer to use facades in Laravel, you can choose to use the provided ``` ShortURL ``` facade instead of instantiating
the ``` Builder ``` class manually.

The example below shows an example of how you could use the facade to create a shortened URL:

```php
<?php

namespace App\Http\Controllers;

use ShortURL;

class Controller
{
    public function index()
    {
        $shortURLObject = ShortURL::destinationUrl('https://destination.com')->make();
        ...
    }
}
```

#### Conditionals

The `Builder` class uses the `Illuminate\Support\Traits\Conditionable` trait, so you can use the `when` and `unless` methods when building your short URLs.

For example, let's take this block of code that uses `if` when building the short URL:

```php
use AshAllenDesign\ShortURL\Classes\Builder;
 
$shortURLObject = (new Builder())
    ->destinationUrl('https://destination.com');

if ($request->date('activation')) {
    $builder = $builder->activateAt($request->date('activation'));
};

$shortURLObject = $builder->make();)
```

This could be rewritten using `when` like so:

 ```php
use AshAllenDesign\ShortURL\Classes\Builder;
use Carbon\Carbon;
 
$shortURLObject = (new Builder())
    ->destinationUrl('https://destination.com')
    ->when(
        $request->date('activation'),
        function (Builder $builder, Carbon $activateDate): Builder  {
            return $builder->activateAt($activateDate);
        },
    )
    ->make();
 ```

### Using the Shortened URLs
#### Default Route and Controller
By default, the shortened URLs that are created use the package's route and controller. The routes use the following structure:
``` https://webapp.com/short/{urlKey} ```. This route uses the single-use controller that is found at 
``` \AshAllenDesign\ShortURL\Controllers\ShortURLController ```.

#### Custom Route
You may wish to use a different routing structure for your shortened URLs other than the default URLs that are created.
For example, you might want to use ``` https://webapp.com/s/{urlKey} ``` or ``` https://webapp.com/{urlKey} ```. You can
customise this to suit the needs of your project.

To use the custom routing all you need to do is add a web route to your project that points to the ShortURLController and
uses the ``` {shortURLKey} ``` field.

The example below shows how you could add a custom route to your ``` web.php ``` file to use the shortened URLs:
```php
Route::get('/custom/{shortURLKey}', '\AshAllenDesign\ShortURL\Controllers\ShortURLController');
```

Note: If you use your own custom routing, you might want to disable the default route that the app provides. Details are
provided for this in the [Customisation](#customisation) section below.

### Tracking
If tracking is enabled for a shortened URL, each time the link is visited, a new ShortURLVisit row in the database will
be created. By default, the package is set to record the following fields of a visitor:

- IP Address
- Browser Name
- Browser Version
- Operating System Name
- Operating System Version
- Referer URL (the URL that the visitor originally came from)
- Device Type (can be: ```desktop```/```mobile```/```tablet```/```robot```)

Each of these fields can be toggled in the config files so that you only record the fields you need. Details on how to 
do this are provided for this in the [Customisation](#customisation) section below.

### Customisation

#### Customising the Default Route

#### Customising the Default URL

The package comes with a route that you can use for your short URLs. By default, this route uses your Laravel app's `app.url` config field to build the URL.

However, you might want to override this and use a different URL for your short URLs. For instance, you might want to use a different domain name for your short URLs.

To override the base URL, you can set the `default_url` config field. For example, to set the base URL to `https://example.com`, you can set the `default_url` in your `config/short-url.php` file like so:

```php
'default_url' => 'https://example.com',
```

To use the your application's `app.url` config field, you can set the `short_url.default_url` field to `null`.

##### Customising the Prefix

The package comes with a route that you can use for your short URLs. By default, this route is `/short/{shortURLKey}`.

You might want to keep using this default route but change the `/short/` prefix to something else. To do this, you can change the `prefix` field in the config.

For example, to change the default short URL to `/s`, you could change the config value like so:

```
'prefix' => 's',
```

##### Removing the Prefix

You may also remove the prefix from the default route completely. For example, if you want your short URL to be accessible via `/{shortUrlKey}`, then you can update the `prefix` config value to `null` like so:

```
'prefix' => null,
```

##### Defining Middleware

You may wish to run the default short URL through some middleware in your application. To do this, you can define the middleware that the route should use via the `middleware` config value.

For example, if you have a `MyAwesomeMiddleware` class, you could update your `short-url` config like so:

```
'middleware' => [
    MyAwesomeMiddleware::class,
],
```

You can also use this same approach to define middleware groups rather than individual middleware classes. For example, if you want your default short URL route to use the `web` middleware group, you could update your config like so:

```
'middleware' => [
    'web',
],
```

It's important to note that this middleware will only be automatically applied to the default short URL route that ships with the package. If you are defining your own route, you'll need to apply this middleware to your route yourself.

#### Disabling the Default Route
If you have added your own custom route to your project, you may want to block the default route that the package provides.
You can do this by setting the following value in the config:

```
'disable_default_route' => true,
```
If the default route is disabled, any visitors who go to the ```/short/{shortURLKey}``` route will receive a HTTP 404.

You may want to manually prevent the route from being automatically registered and manually register it yourself in your own routes file. To do this you can add the following code to your routes file (e.g. `web.php`):

```php
\AshAllenDesign\ShortURL\Facades\ShortURL::routes();
```

#### Default URL Key Length 
When building a shortened URL, you have the option to define your own URL key or to randomly generate one. If one is
randomly generated, the minimum length of it is determined from the config.

A minimum key length of 3 has been enforced for performance reasons. 

For example, to create a shortened URL with a key length of 10 characters, you could set the following in the config:

```
'key_length' => 10,
``` 

By default, the shortened URLs that are created have a key length of 5.

Please be aware that the key length that you specify in the config is only a desirable length. It acts as a minimum length
rather than a fixed length. For example, if the ``` key_length ``` is set to 3 in the config and there is a unique 3 character
long key that hasn't been used yet, the key created will be 3 characters long. However, if all of the possible 3 character long
keys are taken, a 4 character key will be created.

The [Hashids](https://github.com/vinkla/hashids) library is used to assist with creating the URL keys.

#### Tracking Visits
By default, the package enables tracking of all the available fields on each URL built. However, this can be toggled in
the config file.

##### Default Tracking
To disable tracking by default on all future short URLs that are generated, set the following in the config:
```
'tracking'   => [
        'default_enabled' => true,
        ...
]
```
Note: Disabling tracking by default won't disable tracking for any shortened URLs that already exist. It will only apply
to all shortened URLs that are created after the config update.

##### Tracking Fields
You can toggle the default options for each of fields that can be tracked by changing them in the config. These options
can then be overridden for each short URL at the point of creation, as shown in the [Tracking Visitors](#tracking-visitors) section.

For example, the snippet below shows how we could record all of the fields apart from the IP address of the visitor:

```
'tracking'   => [
        ...
        'fields' => [
            'ip_address'               => false,
            'operating_system'         => true,
            'operating_system_version' => true,
            'browser'                  => true,
            'browser_version'          => true,
            'referer_url'              => true,
            'device_type'              => true,
        ],
    ],
```

#### Config Validation
By default, the values defined in the ``` short-url.php ``` config file are not validated. However, the library contains
a validator that can be used to ensure that your values are safe to use. To enable the config validation, you can set the
following option in the config:

```
'validate_config' => true,
``` 

#### Custom Database Connection

By default, Short URL will use your application's default database connection. But there may be times that you'd like to use a different connection. For example, you might be building a multi-tenant application that uses a separate connection for each tenant, and you may want to store the short URLs in a central database.

To do this, you can set the connection name using the `connection` config value in the `config/short-url.php` file like so:

```
'connection' => 'custom_database_connection_name',
```

### Helper Methods
#### Visits
The ShortURL model includes a relationship (that you can use just like any other Laravel model relation) for getting the
visits for a shortened URL.

To get the visits using the relationship, use ``` ->visits ``` or ``` ->visits() ```. The example snippet belows shows how:

```php
$shortURL = \AshAllenDesign\ShortURL\Models\ShortURL::find(1);
$visits = $shortURL->visits;
``` 
#### Find by URL Key
To find the ShortURL model that corresponds to a given shortened URL key, you can use the ``` ->findByKey() ``` method.

For example, to find the ShortURL model of a shortened URL that has the key ``` abc123 ```, you could use the following:
```php
$shortURL = \AshAllenDesign\ShortURL\Models\ShortURL::findByKey('abc123');
``` 

#### Find by Destination URL
To find the ShortURL models that redirect to a given destination URL, you can use the ``` ->findByDestinationURL() ``` method.

For example, to find all of the ShortURL models of shortened URLs that redirect to ``` https://destination.com ```, you could use
the following:

```php
$shortURLs = \AshAllenDesign\ShortURL\Models\ShortURL::findByDestinationURL('https://destination.com');
```

#### Tracking Enabled
To check if tracking is enabled for a short URL, you can use the ``` ->trackingEnabled() ``` method. It will return ``` true ```
if tracking is enabled, and ``` false ``` if not.

The following example shows how to check if a short URL has tracking enabled:

```php
$shortURL = \AshAllenDesign\ShortURL\Models\ShortURL::first();
$shortURL->trackingEnabled();
``` 

#### Tracked Fields
To check which fields are enabled for tracking for a short URL, you can use the ``` ->trackingFields() ``` method. It
will return an array with the names of each field that is currently enabled for tracking.

Note: Even if the tracking options (such as ``` track_ip_address ```) are enabled for a short URL and returned, they
won't be recorded unless the ``` track_visits ``` options is enabled. This can come in handy if you want to enable/disable
tracking for a short URL without needing to individually set each option.

The following example shows how to get an array of all tracking-enabled fields for a short URL:

```php
$shortURL = \AshAllenDesign\ShortURL\Models\ShortURL::first();
$shortURL->trackingFields();
``` 

### Model Factories

The package comes with model factories included for testing purposes which come in handy when generating polymorphic relationships. The `ShortURL` model factory also comes with extra states that you may use when necessary, such as `deactivated` and `inactive`:

```php
use AshAllenDesign\ShortURL\Models\ShortURL;

$shortUrl = ShortURL::factory()->create();

// URL is deactivated
$deactivatedShortUrl = ShortURL::factory()->deactivated()->create();

// URL is neither activated nor deactivated
$inactiveShortURL = ShortURL::factory()->inactive()->create();
```

If you are using your own custom model factory, you can define the factories that the `ShortURL` and `ShortURLVisit` models should use by updating the `factories` config field:

```php
'factories' => [
    \AshAllenDesign\ShortURL\Models\ShortURL::class => \AshAllenDesign\ShortURL\Models\Factories\ShortURLFactory::class,
    \AshAllenDesign\ShortURL\Models\ShortURLVisit::class => \AshAllenDesign\ShortURL\Models\Factories\ShortURLVisitFactory::class
],
```

### Events

#### Short URL Visited
 
Each time a short URL is visited, the following event is fired that can be listened on:

```
AshAllenDesign\ShortURL\Events\ShortURLVisited
```

If you are redirecting users with a `301` HTTP status code, it's possible that this event will NOT be fired
if a visitor has already visited this short URL before. This is due to the fact that most browsers will cache the
intended destination URL as a 'permanent redirect' and won't actually visit the short URL first.

For better results, use the `302` HTTP status code as most browsers will treat the short URL as a 'temporary redirect'.
This means that the short URL will be visited in the browser and the event will be dispatched as expected before redirecting
to the destination URL.

## Testing

To run the package's unit tests, run the following command:

``` bash
vendor/bin/phpunit
```

## Security

If you find any security related issues, please contact me directly at [mail@ashallendesign.co.uk](mailto:mail@ashallendesign.co.uk) to report it.

## Contribution

If you wish to make any changes or improvements to the package, feel free to make a pull request.

Note: A contribution guide will be added soon.

## Credits

- [Ash Allen](https://ashallendesign.co.uk)
- [Jess Pickup](https://jesspickup.co.uk) (Logo)
- [Nathan Giesbrecht](https://github.com/NathanGiesbrecht)
- [Carlos A. Escobar](https://github.com/carlosjs23)
- [Victor-Emil Rossil Andersen](https://github.com/Victor-emil)
- [Julien Arcin](https://github.com/julienarcin)
- [Ryan Chandler](https://github.com/ryangjchandler)
- [All Contributors](https://github.com/ash-jc-allen/short-url/graphs/contributors)

## Changelog

Check the [CHANGELOG](CHANGELOG.md) to get more information about the latest changes.

## Upgrading

Check the [UPGRADE](UPGRADE.md) guide to get more information on how to update this library to newer versions.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Support Me

If you've found this package useful, please consider buying a copy of [Battle Ready Laravel](https://battle-ready-laravel.com) to support me and my work.

Every sale makes a huge difference to me and allows me to spend more time working on open-source projects and tutorials.

To say a huge thanks, you can use the code **BATTLE20** to get a 20% discount on the book.

[👉 Get Your Copy!](https://battle-ready-laravel.com)

[![Battle Ready Laravel](https://ashallendesign.co.uk/images/custom/sponsors/battle-ready-laravel-horizontal-banner.png)](https://battle-ready-laravel.com)
