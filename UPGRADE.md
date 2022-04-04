# Upgrade Guide

## Contents
- [Upgrading from 6.* to 7.0.0](#upgrading-from-6-to-700)
- [Upgrading from 5.* to 6.0.0](#upgrading-from-5-to-600)
- [Upgrading from 4.* to 5.0.0](#upgrading-from-4-to-500)
- [Upgrading from 3.* to 4.0.0](#upgrading-from-3-to-400)
- [Upgrading from 2.* to 3.0.0](#upgrading-from-2-to-300)
- [Upgrading from 1.* to 2.0.0](#upgrading-from-1-to-200)

## Upgrading from 6.* to 7.0.0

### Method Signature Update

As of Short URL v7.0.0, one of the method's signatures have been updated in order to allow the default short URL prefix to be nullable.

The signature of the `prefix()` method in the `AshAllenDesign\ShortURL\Classes\Builder` class has changed from:

```
public function prefix(): string
```

to:

```
public function prefix(): ?string
```

Although it's unlikely that you are overriding this method, if you are, you'll need to update the method signature to the new format.

## Upgrading from 5.* to 6.0.0

### Laravel - Minimum Required Version

As of Short URL v6.0.0, Laravel 6.0 and 7.0 are no longer supported. Therefore, you must be using a minimum of Laravel 8.0 to use this library.

### PHP - Minimum Required Version

As of Short URL v6.0.0, PHP 7.3 and 7.4 are no longer supported. Therefore, you must be using a minimum of PHP 8.0 to use this library.

### New Config Variable and Migration

As of Short URL v6.0.0, you can now forward query parameters from your request onto the destination URL. This feature requires that you run a new migration to add the `forward_query_params` field to your `short_urls` table.

To publish the migration to your own `database/migrations` folder, run the following command in your project root:

```bash
php artisan vendor:publish --tag="short-url-migrations"
```

There is also a new `forward_query_params` config option (that defaults to `false`) for controlling the default behaviour of this feature. If you wish to override this option, you can add the following to your own config:

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

Prior to v5.0.0 of Short URL, the database migrations would be automatically loaded via the package's service provider. As of
v5.0.0, it's now mandatory for the migrations to be published as they won't be automatically loaded anymore.

To publish the migrations to your own ` database/migrations ` folder, run the following command in your project root:

```bash
php artisan vendor:publish --tag="short-url-migrations"
```

## Upgrading from 3.* to 4.0.0

### Laravel - Minimum Required Version
As of Short URL v4.0.0, Laravel 5.8 is no longer supported. Therefore, you must be using a minimum of Laravel 6.0 to use this library.

### New Config Variable
Up until now, the values defined in the ``` short-url.php ``` config file were always validated. However, this sometimes caused issues 
if the application's config was cached before running ``` composer require ```. A new config variable has been added which can
now be used to toggle whether if the validation should be run. By default, the validation is now disabled.

To enable the validation, you can add the following line to your ``` short-url.php ``` config file:

```
'validate_config' => true,
``` 

### Deprecated Facade
As mentioned in [Upgrading from 2.* to 3.0.0](#upgrading-from-2-to-300), the ``` ShortURLBuilder ``` facade was deprecated
and set to be removed. As of Short URL v4.0.0, it has now been removed in favour of the newer ``` ShortURL ``` facade.

## Upgrading from 2.* to 3.0.0

### Migrations and Database Changes
There is now a new database migration that adds 2 additional columns (``` activated_at ```, ``` deactivated_at ```) to the ``` short_urls ``` table. To use these migrations
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
for syntactic sugar. Simply, replace anywhere in your application that uses the ``` ShortURLBuilder ``` with ``` ShortURL ```.

Note: The ``` ShortURLBuilder ``` will remain in version 3.* of the library but will be removed in version 4.0.0.

## Upgrading from 1.* to 2.0.0

### Migrations and Database Changes
There are now 2 new database migrations that add additional columns to the ``` short_urls ``` and ``` short_url_visits ```
tables. To use these migrations to add the columns to your tables, you can run the following command:

```
php artisan migrate
```

If you would prefer to publish the migrations so that you can make changes to them yourself, you can run the following
command before migrating:

```
php artisan vendor:publish --provider="AshAllenDesign\ShortURL\Providers\ShortURLProvider"
```

Note: When this migration runs, it will auto-populate any of your existing short URLs to have the tracking values as specified in your
config. For example, if you have all tracking options except from ``` ip_address ``` enabled in your config, this means
that all of your existing short URLs in the database will explicitly have all tracking options enabled except from the
``` ip_address ```. 

### Config Updates
Two new tracking fields have now been added to the config file. These fields can be used for tracking the referer URL and
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
