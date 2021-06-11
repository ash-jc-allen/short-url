# Changelog

**v5.1.0 (released 2021-06-11):**
- Migrated the CI tests to be run using GitHub Actions instead of Travis CI. [#67](https://github.com/ash-jc-allen/short-url/pull/67)

**v5.0.0 (released 2021-04-18):**
- Removed the automatic loading of the migrations and made it mandatory for them to be published. [#61](https://github.com/ash-jc-allen/short-url/pull/61)

**v4.3.0 (released 2021-04-12):**
- Updated the private fields in the ` Builder ` class to be protected. [#62](https://github.com/ash-jc-allen/short-url/pull/62)

**v4.2.0 (released 2021-01-26):**
- Added support for PHP 8. [#58](https://github.com/ash-jc-allen/short-url/pull/58)

**v4.1.1 (released 2020-09-16):**
- Updated the Travis CI config to run the tests on the correct Laravel versions. [#55](https://github.com/ash-jc-allen/short-url/pull/55)

**4.1.0 (released 2020-09-08):**
- Added support for Laravel 8. [#54](https://github.com/ash-jc-allen/short-url/pull/54)

**4.0.0 (released 2020-07-07):**
- Added a new config value that can be used to toggle the config validation. [#50](https://github.com/ash-jc-allen/short-url/pull/50)
- Removed support for Laravel 5.8. [#51](https://github.com/ash-jc-allen/short-url/pull/51)
- Removed the ``` ShortURLBuilder ``` facade that was deprecated in v3.0.0. [#52](https://github.com/ash-jc-allen/short-url/pull/52)
- Documentation updates. [#48](https://github.com/ash-jc-allen/short-url/pull/48)

**3.0.0 (released 2020-04-11):**
- Added the functionality to set activation and deactivation times for the short URLs. [#46](https://github.com/ash-jc-allen/short-url/pull/46)
- Deprecated the ``` ShortURLBuilder ``` facade in favour of a newer ``` ShortURL ``` facade. [#45](https://github.com/ash-jc-allen/short-url/pull/45)

**2.3.1 (released 2020-03-11):**
- Updated the documentation to mention that the BC Math or GMP PHP extensions are required. [#43](https://github.com/ash-jc-allen/short-url/pull/43)

**2.3.0 (released 2020-03-05):**
- Added support for Laravel 7. [#38](https://github.com/ash-jc-allen/short-url/pull/38)
- Updated the asset publishing tags from ``` config ``` and ``` migrations ``` to ``` short-url-config ``` and 
``` short-url-migrations ```. [#39](https://github.com/ash-jc-allen/short-url/pull/39)

**2.2.0 (released 2020-02-27):**
- Added a default option to enforce HTTPS on destination URLs as a config option. [#36](https://github.com/ash-jc-allen/short-url/pull/36) 

**2.1.0 (released 2020-02-19):**
- Added the key salt (used for generating random URL keys) as a config option. [#32](https://github.com/ash-jc-allen/short-url/pull/32)

**2.0.0 (released 2020-02-14):**
- Added the functionality to track a visitor's referer URL.
- Added the functionality to track a user's device type.
- Added the functionality to explicitly set the tracking options on for each specific short URL. Previously, the options
were set in the config and affected all new and existing short URLs.
- Added the functionality to explicitly set the HTTP status code for the redirect.
- Added a ``` ShortURLVisited``` event that is dispatched when the short URL is used.
- Added the ``` trackingEnabled() ``` and ``` trackingFields() ``` helper methods to the ``` ShortURL ``` model.

**1.2.1 (released 2020-01-13):**
- Fixed a bug that allowed multiple visits to a single-use URL if the URL's visit tracking was disabled.
[Pull Request #23](https://github.com/ash-jc-allen/short-url/pull/23)

**1.2.0 (released 2020-01-03):**
- Renamed the underlying facade class from ``` BuilderFacade ``` to ``` ShortURLBuilder ``` for consistency.

    Note: This isn't
    changing the name of the facade. It's just changing the underlying class name for if you want to use ``` use AshAllenDesign\ShortURL\Facades\ShortURLBuilder; ```
    rather than ``` use ShortURLBuilder; ```.

- Added methods to the facade docblock. This will display the methods in the IDE's autocomplete.

**1.1.0 (released 2020-01-03):**
- Enforced a minimum length of 3 for the URL ``` key_length ``` that is specified in the config.
- Included [hashids/hasids](https://github.com/vinkla/hashids) as a dependency. This is now used for generating the random, unique URL keys.
- Updated documentation.

**1.0.0 (released 2020-01-02):**
- Release for production.
- Added a ```ShortURLBuilder``` facade.
- Refactored folder structure to meet standards.
- Updated documentation (thanks [@NathanGiesbrecht](https://github.com/NathanGiesbrecht))
- Updated tests and Travis CI configuration for PHP 7.4 testing.

**0.0.1 (pre-release):**
- Initial work and pre-release testing.
