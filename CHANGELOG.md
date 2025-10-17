# Changelog

**v8.4.1 (released 2025-10-17):**

- Updated `.gitattributes` file to include correct files. [#321](https://github.com/ash-jc-allen/short-url/pull/321)

**v8.4.0 (released 2025-10-17):**

- Added `.gitattributes` file. [#320](https://github.com/ash-jc-allen/short-url/pull/320)

**v8.3.1 (released 2025-05-27):**

- Fixed bug that prevented the migrations from running when using MySQL. [#315](https://github.com/ash-jc-allen/short-url/pull/315)
- Added GitHub Actions workflows to run the tests with MySQL and PostgreSQL. [#314](https://github.com/ash-jc-allen/short-url/pull/314)

**v8.3.0 (released 2025-02-24):**

- Added support for Laravel 12. [#308](https://github.com/ash-jc-allen/short-url/pull/308)
- Added support for Larastan 3. [#309](https://github.com/ash-jc-allen/short-url/pull/309)
- Added support for Pest 3. [#308](https://github.com/ash-jc-allen/short-url/pull/308)

**v8.2.1 (released 2024-12-03):**

- Removed `laravel/framework` dependency that was accidentally added. [7f3bc3b](https://github.com/ash-jc-allen/short-url/commit/7f3bc3b4beee87850f8172bd4ca3f86722910649)

**v8.2.0 (released 2024-12-01):**

- Added support for PHP 8.4. [#301](https://github.com/ash-jc-allen/short-url/pull/301)

**v8.1.2 (released 2024-09-17):**

- Fixed bug that threw an exception if the `short_url.id` column was returned as a string from the database. [#297](https://github.com/ash-jc-allen/short-url/pull/297)

**v8.1.1 (released 2024-08-27):**

- Fixed bug that threw an exception if the OS name could not be determined from the user agent. [#295](https://github.com/ash-jc-allen/short-url/pull/295)

**v8.1.0 (released 2024-07-02):**

- Added `allowable_url_schemes` config value to allow for custom URL schemes to be used when creating short URLs. [#287](https://github.com/ash-jc-allen/short-url/pull/287), [#288](https://github.com/ash-jc-allen/short-url/pull/288)

**v8.0.1 (released 2024-06-14):**

- Fixed bug that threw an exception when creating a new Short URL and Laravel is set to use `\Carbon\CarbonImmutable` dates. [#285](https://github.com/ash-jc-allen/short-url/pull/285)

**v8.0.0 (released 2024-04-26):**

- Switch from `jenssegers/agent` to `whichbrowser/parser` for user agent parsing. [#261](https://github.com/ash-jc-allen/short-url/pull/261), [#256](https://github.com/ash-jc-allen/short-url/pull/256)
- Added property types and strict type-checking. [#265](https://github.com/ash-jc-allen/short-url/pull/265)
- Added support for PHPUnit 11. [#268](https://github.com/ash-jc-allen/short-url/pull/268)
- Added Pest architecture tests. [#274](https://github.com/ash-jc-allen/short-url/pull/274)
- Switch to `ashallendesign/laravel-config-validator` to validate the package's config. [#278](https://github.com/ash-jc-allen/short-url/pull/278)
- Dropped support for PHP 8.0. [#257](https://github.com/ash-jc-allen/short-url/pull/257)
- Dropped support for Laravel 8 and 9. [#257](https://github.com/ash-jc-allen/short-url/pull/257)
- Dropped support for PHPUnit 8 and 9. [#268](https://github.com/ash-jc-allen/short-url/pull/268)
- Switched from "nunomaduro/larastan" to "larastan/larastan". [#267](https://github.com/ash-jc-allen/short-url/pull/267)
- Removed the `date` property from the `ShortURL` and `ShortURLVisit` models. [#266](https://github.com/ash-jc-allen/short-url/pull/266)
- Abstract the key generator out to use an interface. [#262](https://github.com/ash-jc-allen/short-url/pull/262)
- Updated the facade to include all methods in the docblock. [#252](https://github.com/ash-jc-allen/short-url/pull/252)

Huge thank you to [@stevebauman](https://github.com/stevebauman) and [@marzvrover](https://github.com/marzvrover) for their contributions in this release. I really appreciate it! 🚀

**v7.10.0 (released 2024-03-19):**

- Added support for nesbot/carbon 3.0. [#246](https://github.com/ash-jc-allen/short-url/pull/246)

**v7.9.0 (released 2024-03-12):**

- Added support for Laravel 11. [#239](https://github.com/ash-jc-allen/short-url/pull/239)
- Only add the "utf8mb4_bin" collation if using MySQL. [#241](https://github.com/ash-jc-allen/short-url/pull/241)

**v7.8.2 (released 2024-02-12):**
- Fixed a bug in the `ShortURL` model that prevented a boolean field from being cast. [#234](https://github.com/ash-jc-allen/short-url/pull/234)
- Added missing methods to the `ShortURL` facade's docblock. [#233](https://github.com/ash-jc-allen/short-url/pull/233)

**v7.8.1 (released 2023-12-28):**
- Fixed bug in a database migration that was using the wrong database connection. [#228](https://github.com/ash-jc-allen/short-url/pull/228)
- Code style fixes. [#230](https://github.com/ash-jc-allen/short-url/pull/230)

**v7.8.0 (released 2023-11-11):**
- Added the ability to store custom database fields when creating short URLs. [#225](https://github.com/ash-jc-allen/short-url/pull/225)

**v7.7.0 (released 2023-10-26):**
- Added the ability to specify the database connection for the package's models. [#214](https://github.com/ash-jc-allen/short-url/pull/214)
- Improve the query key generation performance. [#213](https://github.com/ash-jc-allen/short-url/pull/213)
- Added PHP 8.3 CI tests. [#217](https://github.com/ash-jc-allen/short-url/pull/217)

**v7.6.0 (released 2023-03-21):**
- Added support for `hashids/hashids` v5.0. [#183](https://github.com/ash-jc-allen/short-url/pull/183)
- Added the ability to pass a seed that can be used when generating the short URL key. [#185](https://github.com/ash-jc-allen/short-url/pull/185)

**v7.5.1 (released 2023-02-01):**
- Added missing date fields to the `casts` array on the models to support Laravel 10. [#181](https://github.com/ash-jc-allen/short-url/pull/181)

**v7.5.0 (released 2023-01-28):**
- Added the ability to override the domain of the default URL. [#173](https://github.com/ash-jc-allen/short-url/pull/173)

**v7.4.0 (released 2023-01-11):**
- Added support for Laravel 10. [3488417](https://github.com/ash-jc-allen/short-url/commit/348841713d87e8259fcddcc610c7d68e3c3caa42)
- Added support for Larastan 2.0. [#169](https://github.com/ash-jc-allen/short-url/pull/169)

**v7.3.0 (released 2022-10-17):**
- Added model factories for the `ShortURL` and `ShortURLVisits` models. [#162](https://github.com/ash-jc-allen/short-url/pull/162)

**v7.2.0 (released 2022-09-12):**
- Added support for PHP 8.2. [#139](https://github.com/ash-jc-allen/short-url/pull/139)
- Added support for using `when` when building short URLs. [#140](https://github.com/ash-jc-allen/short-url/pull/140)
- Removed unused `URL` facade import. [#147](https://github.com/ash-jc-allen/short-url/pull/147)
- Fixed bug that always resulted in a 404 if the `prefix` was set to `null`. [#149](https://github.com/ash-jc-allen/short-url/pull/149), [#158](https://github.com/ash-jc-allen/short-url/pull/158)

**v7.1.0 (released 2022-08-03):**
- Added `toArray` method to the `Builder` class. [#133](https://github.com/ash-jc-allen/short-url/pull/133)
- Fixed `shortURL` relationship on the `ShortURLVisit` model. [#132](https://github.com/ash-jc-allen/short-url/pull/132)

**v7.0.0 (released 2022-04-04):**
- Added ability to remove the prefix from default short URLs. [#123](https://github.com/ash-jc-allen/short-url/pull/123)
- Added ability to define middleware for the default short URL route. [#121](https://github.com/ash-jc-allen/short-url/pull/121)
- Added ability to set the key generator on-the-fly. [#122](https://github.com/ash-jc-allen/short-url/pull/122)

**v6.3.0 (released 2022-01-24):**
- Added support for Laravel 9. [#116](https://github.com/ash-jc-allen/short-url/pull/116)

**v6.2.0 (released 2021-11-26):**
- Updated incorrectly set file permissions. [#104](https://github.com/ash-jc-allen/short-url/pull/104)
- Updated GitHub Actions to run workflow with PHP 8.1. [#106](https://github.com/ash-jc-allen/short-url/pull/106)
- Added support for PHPUnit ^9.0. [#101](https://github.com/ash-jc-allen/short-url/pull/101)
- Added support for Larastan ^1.0. [#107](https://github.com/ash-jc-allen/short-url/pull/107)

**v6.1.0 (released 2021-10-21):**
- Added the ability to customise the default short URL prefix. [#100](https://github.com/ash-jc-allen/short-url/pull/100)

**v6.0.0 (released 2021-10-21):**
- Added the ability to forward query parameters to the destination URL. [#94](https://github.com/ash-jc-allen/short-url/pull/94)
- Dropped support for Laravel 6, 7. [#96](https://github.com/ash-jc-allen/short-url/pull/96), [#98](https://github.com/ash-jc-allen/short-url/pull/98)
- Dropped support for PHP 7.3, 7.4. [#85](https://github.com/ash-jc-allen/short-url/pull/85)

**v5.2.0 (released 2021-09-21):**
- Updated the migration for the `short_urls` table so that `url_key` is now unique and `destination_url` is now a TEXT field rather than varchar. [#80](https://github.com/ash-jc-allen/short-url/pull/80)
- Added the ability to configure the alphabet used for generating keys with `hashids`. [#77](https://github.com/ash-jc-allen/short-url/pull/77)

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
