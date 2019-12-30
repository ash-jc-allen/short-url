# Short URL

<div style="text-align:center">

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ashallendesign/short-url.svg?style=flat-square)](https://packagist.org/packages/ashallendesign/short-url)
[![Build Status](https://img.shields.io/travis/ash-jc-allen/short-url/master.svg?style=flat-square)](https://travis-ci.org/ash-jc-allen/short-url)
[![Total Downloads](https://img.shields.io/packagist/dt/ashallendesign/short-url.svg?style=flat-square)](https://packagist.org/packages/ashallendesign/short-url)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/ashallendesign/short-url?style=flat-square)](https://img.shields.io/packagist/php-v/ashallendesign/short-url)
[![GitHub license](https://img.shields.io/github/license/ash-jc-allen/short-url?style=flat-square)](https://github.com/ash-jc-allen/short-url/blob/master/LICENSE)

</div>

## Table of Contents

- [Overview](#overview)
- [Installation](#installation)
- [Usage](#usage)
- [Testing](#testing)
- [Security](#security)
- [Contribution](#contribution)
- [License](#license)
    
## Overview

A Laravel package that can be used for adding shortened URLs to your existing web app.

## Installation

### Requirements
The package has been developed and tested to work with the following minimum requirements:

- PHP 7.2
- Laravel 5.8

### Install the Package
You can install the package via Composer:

```bash
composer require ashallendesign/short-url
```

### Publish the Config
You can then publish the package's config file (so that you can make changes to it) by using the following command:
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

Note: All of the URL keys are unique, so you cannot use a key that has already exists in the database for another shortened
URL.

#### Tracking Visitors
You may want to track some data about the visitors that have used the shortened URL. This can be useful for analytics.
By default, tracking is enabled and all of the available tracking fields are also enabled. You can toggle the different
parts of the tracking in the config file. Read further on in the [Customisation](#customisation) section to see how to
customise the default tracking behaviours.

If you want to override whether if tracking is enabled or not when creating a shortened URL, you can use the ``` ->trackVisits() ``` method.
This method simply accepts a boolean.

The example below shows how to enable tracking for the URL and override the config variable:
```php
$builder = new \AshAllenDesign\ShortURL\Classes\Builder();

$shortURLObject = $builder->destinationUrl('https://destination.com')->trackVisits()->make();
```

The example below shows how to disable tracking for the URL and override the config variable:
```php
$builder = new \AshAllenDesign\ShortURL\Classes\Builder();

$shortURLObject = $builder->destinationUrl('https://destination.com')->trackVisits(false)->make();
```

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
When building a shortened URL, you might want to enforce that enforce that the visitor is redirected to the HTTPS version
of the destination URL. This can be particularly useful if you're allowing your web app users to create their own shortened
URLS.

To enforce HTTPS, you can use the ``` ->secure() ``` method when building the shortened URL.

The example below show how to create a secure shortened URL:
 ```php
 $builder = new \AshAllenDesign\ShortURL\Classes\Builder();
 
 $shortURLObject = $builder->destinationUrl('https://destination.com')->secure()->make();
 ```

### Using the Shortened URLs

### Customisation
#### Customising the Config
#### Custom Routing

## Testing

``` bash
vendor/bin/phpunit
```

## Security

If you find any security related, please contact me directly at [mail@ashallendesign.co.uk](mailto:mail@ashallendesign.co.uk) to report it.

## Contribution

If you wish to make any changes or improvements to the package, feel free to make a pull request.

Note: A contribution guide will be added soon.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
