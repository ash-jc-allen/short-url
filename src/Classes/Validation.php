<?php

namespace AshAllenDesign\ShortURL\Classes;

use AshAllenDesign\ShortURL\Exceptions\ValidationException;

class Validation
{
    /**
     * Validate all of the config related to the
     * library.
     *
     * @return bool
     * @throws ValidationException
     */
    public function validateConfig(): bool
    {
        return $this->validateKeyLength()
               && $this->validateTrackingOptions()
               && $this->validateDefaultRouteOption()
               && $this->validateKeySalt()
               && $this->validateEnforceHttpsOption();
    }

    /**
     * Validate that the URL Length parameter specified
     * in the config is an integer that is above 0.
     *
     * @return bool
     * @throws ValidationException
     */
    protected function validateKeyLength(): bool
    {
        $urlLength = config('short-url.key_length');

        if (! is_int($urlLength)) {
            throw new ValidationException('The config URL length is not a valid integer.');
        }

        if ($urlLength < 3) {
            throw new ValidationException('The config URL length must be 3 or above.');
        }

        return true;
    }

    /**
     * Assert that the key salt provided in the config is
     * valid.
     *
     * @return bool
     * @throws ValidationException
     */
    protected function validateKeySalt(): bool
    {
        $keySalt = config('short-url.key_salt');

        if (! is_string($keySalt)) {
            throw new ValidationException('The config key salt must be a string.');
        }

        if (! strlen($keySalt)) {
            throw new ValidationException('The config key salt must be at least 1 character long.');
        }

        return true;
    }

    /**
     * Validate that each of the tracking options are
     * booleans.
     *
     * @return bool
     * @throws ValidationException
     */
    protected function validateTrackingOptions(): bool
    {
        $trackingOptions = config('short-url.tracking');

        if (! is_bool($trackingOptions['default_enabled'])) {
            throw new ValidationException('The default_enabled config variable must be a boolean.');
        }

        foreach ($trackingOptions['fields'] as $trackingOption => $value) {
            if (! is_bool($value)) {
                throw new ValidationException('The '.$trackingOption.' config variable must be a boolean.');
            }
        }

        return true;
    }

    /**
     * Validate that the disable_default_route option
     * is a boolean.
     *
     * @return bool
     * @throws ValidationException
     */
    protected function validateDefaultRouteOption(): bool
    {
        if (! is_bool(config('short-url.disable_default_route'))) {
            throw new ValidationException('The disable_default_route config variable must be a boolean.');
        }

        return true;
    }

    /**
     * Validate that the enforce_https option is a boolean.
     *
     * @return bool
     * @throws ValidationException
     */
    protected function validateEnforceHttpsOption(): bool
    {
        if (! is_bool(config('short-url.enforce_https'))) {
            throw new ValidationException('The enforce_https config variable must be a boolean.');
        }

        return true;
    }
}
