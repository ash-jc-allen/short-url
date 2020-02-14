# Upgrade Guide

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