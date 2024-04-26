# Upgrade Guide

## Contents

- [Upgrading from 7.* to 8.0.0](#upgrading-from-7-to-800)
- [Upgrading from 6.* to 7.0.0](#upgrading-from-6-to-700)
- [Upgrading from 5.* to 6.0.0](#upgrading-from-5-to-600)
- [Upgrading from 4.* to 5.0.0](#upgrading-from-4-to-500)
- [Upgrading from 3.* to 4.0.0](#upgrading-from-3-to-400)
- [Upgrading from 2.* to 3.0.0](#upgrading-from-2-to-300)
- [Upgrading from 1.* to 2.0.0](#upgrading-from-1-to-200)

## Upgrading from 7.* to 8.0.0

### Laravel - Minimum Required Version

As of Short URL v8.0, support for Laravel 8 and 9 has been dropped. This means that you must be using a minimum of
Laravel 10.0 to use this package.

### PHP - Minimum Required Version

As of Short URL v8.0, support for PHP 8.0 has been dropped. This means that you must be using a minimum of PHP 8.1 to
use this package.

### Storing Undetectable Tracking Fields as `null` instead of `false`

Previously, if the user agent parser was unable to detect the operating system, browser, or device type, the tracking fields would be stored as false. For example, if the user agent parser was unable to detect the browser version of the visitor, the `browser_version` field in the `short_url_visits` table would be stored as `false`.

As of Short URL v8.0, these fields will now be stored as `null` instead. This change has been made to better reflect the fact that the tracking field was unable to be detected, rather than storing a boolean value that could be misleading.

If you've been relying on the tracking fields being stored as `false` in your own code, you may need to update your code to reflect the new `null` values.

Please note: I won't be providing a required database migration as part of the package to update the existing `false` values to `null`. But you'll likely want to update your existing rows in the `short_url_visits` table to convert any `false` values to `null` so that they'll be consistent with your newer data.

You may want to use this migration provided by [@stevebauman](https://github.com/stevebauman) to update your existing data:

```php
class UpdateShortUrlVisitsTableConvertFalseValuesToNull extends Migration
{
    public function up(): void
    {
        DB::transaction(static function (): void {
            DB::connection(config('short-url.connection'))
                ->table('short_url_visits')
                ->update([
                    'ip_address' => DB::raw("CASE WHEN ip_address = '0' THEN NULL ELSE ip_address END"),
                    'operating_system' => DB::raw("CASE WHEN operating_system = '0' THEN NULL ELSE operating_system END"),
                    'operating_system_version' => DB::raw("CASE WHEN operating_system_version = '0' THEN NULL ELSE operating_system_version END"),
                    'browser' => DB::raw("CASE WHEN browser = '0' THEN NULL ELSE browser END"),
                    'browser_version' => DB::raw("CASE WHEN browser_version = '0' THEN NULL ELSE browser_version END"),
                    'referer_url' => DB::raw("CASE WHEN referer_url = '0' THEN NULL ELSE referer_url END"),
                    'device_type' => DB::raw("CASE WHEN device_type = '0' THEN NULL ELSE device_type END"),
                ]);
        });
    }
}
```

### Added Property Types

In a bid to benefit from PHP's type system, all class-level properties now explicitly have their types defined. So if
you're using any of the following properties in your own code (or overriding any of the classes), you may need to update
your code to reflect the new types:

Class: `AshAllenDesign\ShortURL\Classes\Builder`

| Old property definition                  | New property definition                               |
|------------------------------------------|-------------------------------------------------------|
| `private $keyGenerator`                  | `protected UrlKeyGenerator $keyGenerator`             |
| `protected $destinationUrl`              | `protected ?string $destinationUrl = null`            |
| `protected $singleUse = false`           | `protected ?bool $singleUse = false`                  |
| `protected $secure`                      | `protected ?bool $secure = null`                      |
| `protected $forwardQueryParams`          | `protected ?bool $forwardQueryParams = null`          |
| `protected $trackVisits`                 | `protected ?bool $trackVisits = null`                 |
| `protected $urlKey`                      | `protected ?string $urlKey = null`                    |
| `protected $redirectStatusCode = 301`    | `protected int $redirectStatusCode = 301`             |
| `protected $trackIPAddress`              | `protected ?bool $trackIPAddress = null`              |
| `protected $trackOperatingSystem`        | `protected ?bool $trackOperatingSystem = null`        |
| `protected $trackOperatingSystemVersion` | `protected ?bool $trackOperatingSystemVersion = null` |
| `protected $trackBrowser;`               | `protected ?bool $trackBrowser = null`                |
| `protected $trackBrowserVersion`         | `protected ?bool $trackBrowserVersion = null`         |
| `protected $trackRefererURL`             | `protected ?bool $trackRefererURL = null`             |
| `protected $trackDeviceType = null`      | `protected ?bool $trackDeviceType = null`             |
| `protected $activateAt = null`           | `protected ?Carbon $activateAt = null`                |
| `protected $deactivateAt = null`         | `protected ?Carbon $deactivateAt = null`              |

Class: `AshAllenDesign\ShortURL\Classes\KeyGenerator`

| Old property definition | New property definition    |
|-------------------------|----------------------------|
| `private $hashids`      | `private Hashids $hashids` |

Class: `AshAllenDesign\ShortURL\Classes\Resolver`

| Old property definition | New property definition                    |
|-------------------------|--------------------------------------------|
| `private $agent`        | `private UserAgentDriver $userAgentDriver` |

Class: `AshAllenDesign\ShortURL\Events\ShortURLVisited`

| Old property definition | New property definition               |
|-------------------------|---------------------------------------|
| `public $shortURL`      | `public ShortURL $shortURL`           |
| `public $shortURLVisit` | `public ShortURLVisit $shortURLVisit` |

### Method Signature Changes

Some method signatures have changed as of Short URL v8.0. These changes are either due to the addition of some new interfaces (which should make future maintenance work easier) or to reduce any unnecessary complexity in the codebase.

If you are overriding any of the following methods, or interacting with them in your code, you may need to update your code to reflect the new method signatures.

#### `AshAllenDesign\ShortURL\Classes\Builder@__construct`

The constructor for the `AshAllenDesign\ShortURL\Classes\Builder` class has changed from:

```php
use AshAllenDesign\ShortURL\Classes\KeyGenerator;
use AshAllenDesign\ShortURL\Classes\Validation;

public function __construct(Validation $validation = null, KeyGenerator $keyGenerator = null)
```

to:

```php
use AshAllenDesign\ShortURL\Classes\Validation;
use AshAllenDesign\ShortURL\Interfaces\UrlKeyGenerator;

public function __construct(Validation $validation, UrlKeyGenerator $urlKeyGenerator)
```

If you were previously using `new Builder()` to create your `Builder` instance, you may want to consider switching to `app(Builder::class)` instead. This will allow the package to resolve the class from the container and inject the required dependencies for you.

#### `AshAllenDesign\ShortURL\Classes\Builder@keyGenerator`

The `keyGenerator` method in the `AshAllenDesign\ShortURL\Classes\Builder` class has changed from:

```php
use AshAllenDesign\ShortURL\Classes\KeyGenerator;

public function keyGenerator(KeyGenerator $keyGenerator): self
````

to:

```php
use AshAllenDesign\ShortURL\Interfaces\UrlKeyGenerator;

public function keyGenerator(UrlKeyGenerator $keyGenerator): self
```

If you're interacting with this method and passing in your own instance of the `KeyGenerator` class, you shouldn't need to make any changes since the `KeyGenerator` class now implements the `UrlKeyGenerator` interface. However, if you're overriding this method anywhere, you may need to update the method signature to reflect the new interface.

#### `AshAllenDesign\ShortURL\Classes\KeyGenerator@__construct`

The constructor for the `AshAllenDesign\ShortURL\Classes\KeyGenerator` class has changed from:

```php
use Hashids\Hashids;

public function __construct(Hashids $hashids = null)
```

to:

```php
use Hashids\Hashids;

public function __construct(Hashids $hashids)
```

If you were previously using `new Hashids()` to create your `Hashids` instance, you may want to consider switching to `app(Hashids::class)` instead. This will allow the package to resolve the class from the container and inject the required arguments that you've defined in your config file.

#### `AshAllenDesign\ShortURL\Classes\Resolver@__construct`

The constructor for the `AshAllenDesign\ShortURL\Classes\Resolver` class has changed from:

```php
use AshAllenDesign\ShortURL\Classes\Validation;
use Jenssegers\Agent\Agent;

public function __construct(Agent $agent = null, Validation $validation = null)
```

to:

```php
use AshAllenDesign\ShortURL\Classes\Validation;
use AshAllenDesign\ShortURL\Interfaces\UserAgentDriver;

public function __construct(UserAgentDriver $userAgentDriver, Validation $validation)
```

#### `AshAllenDesign\ShortURL\Classes\Resolver@guessDeviceType`

The `guessDeviceType` method in the `AshAllenDesign\ShortURL\Classes\Resolver` class has changed from:

```php
protected function guessDeviceType(): string
```

to:

```php
protected function guessDeviceType(UserAgentDriver $userAgentParser): ?string
```

## Upgrading from 6.* to 7.0.0

### Method Signature Update

As of Short URL v7.0.0, one of the method's signatures have been updated in order to allow the default short URL prefix
to be nullable.

The signature of the `prefix()` method in the `AshAllenDesign\ShortURL\Classes\Builder` class has changed from:

```
public function prefix(): string
```

to:

```
public function prefix(): ?string
```

Although it's unlikely that you are overriding this method, if you are, you'll need to update the method signature to
the new format.

## Upgrading from 5.* to 6.0.0

### Laravel - Minimum Required Version

As of Short URL v6.0.0, Laravel 6.0 and 7.0 are no longer supported. Therefore, you must be using a minimum of Laravel
8.0 to use this library.

### PHP - Minimum Required Version

As of Short URL v6.0.0, PHP 7.3 and 7.4 are no longer supported. Therefore, you must be using a minimum of PHP 8.0 to
use this library.

### New Config Variable and Migration

As of Short URL v6.0.0, you can now forward query parameters from your request onto the destination URL. This feature
requires that you run a new migration to add the `forward_query_params` field to your `short_urls` table.

To publish the migration to your own `database/migrations` folder, run the following command in your project root:

```bash
php artisan vendor:publish --tag="short-url-migrations"
```

There is also a new `forward_query_params` config option (that defaults to `false`) for controlling the default
behaviour of this feature. If you wish to override this option, you can add the following to your own config:

```php
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
```

## Upgrading from 4.* to 5.0.0

### Publish Migrations

Prior to v5.0.0 of Short URL, the database migrations would be automatically loaded via the package's service provider.
As of
v5.0.0, it's now mandatory for the migrations to be published as they won't be automatically loaded anymore.

To publish the migrations to your own ` database/migrations ` folder, run the following command in your project root:

```bash
php artisan vendor:publish --tag="short-url-migrations"
```

## Upgrading from 3.* to 4.0.0

### Laravel - Minimum Required Version

As of Short URL v4.0.0, Laravel 5.8 is no longer supported. Therefore, you must be using a minimum of Laravel 6.0 to use
this library.

### New Config Variable

Up until now, the values defined in the ``` short-url.php ``` config file were always validated. However, this sometimes
caused issues
if the application's config was cached before running ``` composer require ```. A new config variable has been added
which can
now be used to toggle whether if the validation should be run. By default, the validation is now disabled.

To enable the validation, you can add the following line to your ``` short-url.php ``` config file:

```
'validate_config' => true,
``` 

### Deprecated Facade

As mentioned in [Upgrading from 2.* to 3.0.0](#upgrading-from-2-to-300), the ``` ShortURLBuilder ``` facade was
deprecated
and set to be removed. As of Short URL v4.0.0, it has now been removed in favour of the newer ``` ShortURL ``` facade.

## Upgrading from 2.* to 3.0.0

### Migrations and Database Changes

There is now a new database migration that adds 2 additional columns (``` activated_at ```, ``` deactivated_at ```) to
the ``` short_urls ``` table. To use these migrations
to add the columns to your tables, you can run the following command:

```
php artisan migrate
```

If you would prefer to publish the migrations so that you can make changes to them yourself, you can run the following
command before migrating:

```
php artisan vendor:publish --provider="AshAllenDesign\ShortURL\Providers\ShortURLProvider"
```

Note: When this migrations runs, it will auto-populate any of your existing short URLs to have today's date as the
``` activated_at``` date. However, the ``` deactivated_at ``` column will remain as ``` null ```. This means that the
short URL will remain active indefinitely.

### Deprecated Facade

Up until now, you could use the ``` ShortURLBuilder ``` facade to create a new short URL. However, to make the package
fit more with the Laravel naming conventions and consistency, this has now been deprecated.

There is now a newer ``` ShortURL ``` facade which you can use. It works exactly the same and is purely being changed
for syntactic sugar. Simply, replace anywhere in your application that uses the ``` ShortURLBuilder ```
with ``` ShortURL ```.

Note: The ``` ShortURLBuilder ``` will remain in version 3.* of the library but will be removed in version 4.0.0.

## Upgrading from 1.* to 2.0.0

### Migrations and Database Changes

There are now 2 new database migrations that add additional columns to the ``` short_urls ```
and ``` short_url_visits ```
tables. To use these migrations to add the columns to your tables, you can run the following command:

```
php artisan migrate
```

If you would prefer to publish the migrations so that you can make changes to them yourself, you can run the following
command before migrating:

```
php artisan vendor:publish --provider="AshAllenDesign\ShortURL\Providers\ShortURLProvider"
```

Note: When this migration runs, it will auto-populate any of your existing short URLs to have the tracking values as
specified in your
config. For example, if you have all tracking options except from ``` ip_address ``` enabled in your config, this means
that all of your existing short URLs in the database will explicitly have all tracking options enabled except from the
``` ip_address ```.

### Config Updates

Two new tracking fields have now been added to the config file. These fields can be used for tracking the referer URL
and
device type of visitors.

You can add these options to your config file like shown below:

```
'tracking'   => [
        ...
        'fields' => [
            ...
            'referer_url' => true,
            'device_type' => true,
        ],
    ],
```
