<?php

declare(strict_types=1);

namespace AshAllenDesign\ShortURL\Classes;

use AshAllenDesign\ConfigValidator\Exceptions\InvalidConfigValueException;
use AshAllenDesign\ConfigValidator\Services\ConfigValidator;
use AshAllenDesign\ConfigValidator\Services\Rule;
use AshAllenDesign\ShortURL\Exceptions\ValidationException;

class Validation
{
    /**
     * Validate all the config related to the library.
     *
     * @throws ValidationException
     */
    public function validateConfig(): bool
    {
        try {
            return app(ConfigValidator::class)->runInline([
                'short-url' => [
                    $this->validateKeyLength(),
                    ...$this->validateTrackingOptions(),
                    $this->validateKeySalt(),
                    $this->validateDefaultRouteOption(),
                    $this->validateEnforceHttpsOption(),
                    $this->validateForwardQueryParamsOption(),
                    $this->validateDefaultUrl(),
                ],
            ]);
        } catch (InvalidConfigValueException $e) {
            throw new ValidationException($e->getMessage());
        }
    }

    /**
     * Validate that the URL Length parameter specified in the config is an integer
     * that is above 3.
     */
    protected function validateKeyLength(): Rule
    {
        return Rule::make('key_length')
            ->rules(['required', 'integer', 'min:3'])
            ->messages([
                'required' => 'The config URL length is not a valid integer.',
                'integer' => 'The config URL length is not a valid integer.',
                'min' => 'The config URL length must be 3 or above.'
            ]);
    }

    /**
     * Assert that the key salt provided in the config is valid.
     */
    protected function validateKeySalt(): Rule
    {
        return Rule::make('key_salt')
            ->rules(['required', 'string'])
            ->messages([
                'required' => 'The config key salt must be a string.',
                'string' => 'The config key salt must be a string.',
            ]);
    }

    /**
     * Validate that each of the tracking options are booleans.
     *
     * @throws ValidationException
     */
    protected function validateTrackingOptions(): array
    {
        $trackingOptions = config('short-url.tracking');

        $rules = [
            Rule::make('tracking.default_enabled')
                ->rules(['required', 'boolean'])
                ->messages([
                    'required' => 'The default_enabled config variable must be a boolean.',
                    'boolean' => 'The default_enabled config variable must be a boolean.',
                ]),
        ];

        if (!is_bool($trackingOptions['default_enabled'])) {
            throw new ValidationException('The default_enabled config variable must be a boolean.');
        }

        foreach ($trackingOptions['fields'] as $trackingOption => $value) {
            $rules[] = Rule::make('tracking.fields.' . $trackingOption)
                ->rules(['required', 'boolean'])
                ->messages([
                    'required' => 'The ' . $trackingOption . ' config variable must be a boolean.',
                    'boolean' => 'The ' . $trackingOption . ' config variable must be a boolean.',
                ]);
        }

        return $rules;
    }

    /**
     * Validate that the disable_default_route option is a boolean.
     */
    protected function validateDefaultRouteOption(): Rule
    {
        return Rule::make('disable_default_route')
            ->rules(['required', 'boolean'])
            ->messages([
                'required' => 'The disable_default_route config variable must be a boolean.',
                'boolean' => 'The disable_default_route config variable must be a boolean.',
            ]);
    }

    /**
     * Validate that the enforce_https option is a boolean.
     */
    protected function validateEnforceHttpsOption(): Rule
    {
        return Rule::make('enforce_https')
            ->rules(['required', 'boolean'])
            ->messages([
                'required' => 'The enforce_https config variable must be a boolean.',
                'boolean' => 'The enforce_https config variable must be a boolean.',
            ]);
    }

    /**
     * Validate that the forward query params option is a boolean.
     */
    protected function validateForwardQueryParamsOption(): Rule
    {
        return Rule::make('forward_query_params')
            ->rules(['required', 'boolean'])
            ->messages([
                'required' => 'The forward_query_params config variable must be a boolean.',
                'boolean' => 'The forward_query_params config variable must be a boolean.',
            ]);
    }

    /**
     * Validate that the default URL is a valid string or null.
     */
    protected function validateDefaultUrl(): Rule
    {
        return Rule::make('default_url')
            ->rules(['nullable', 'string'])
            ->messages([
                'nullable' => 'The default_url config variable must be a string or null.',
                'string' => 'The default_url config variable must be a string or null.',
            ]);
    }
}
