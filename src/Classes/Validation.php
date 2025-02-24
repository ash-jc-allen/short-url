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
     * @throws InvalidConfigValueException
     */
    public function validateConfig(): bool
    {
        $validator = app(ConfigValidator::class);

        $passes = $validator
            ->throwExceptionOnFailure(false)
            ->runInline([
                'short-url' => [
                    ...$this->validateTrackingOptions(),
                    Rule::make('key_length')->rules(['required', 'integer', 'min:3']),
                    Rule::make('key_salt')->rules(['required', 'string']),
                    Rule::make('disable_default_route')->rules(['required', 'boolean']),
                    Rule::make('enforce_https')->rules(['required', 'boolean']),
                    Rule::make('forward_query_params')->rules(['required', 'boolean']),
                    Rule::make('default_url')->rules(['nullable', 'string']),
                    Rule::make('allowed_url_schemes')->rules(['required', 'array']),
                ],
            ]);

        if (! $passes) {
            $validationMessage = $validator->errors()[array_key_first($validator->errors())][0];

            throw new ValidationException($validationMessage);
        }

        return $passes;
    }

    /**
     * Validate that each of the tracking options are booleans.
     *
     * @return Rule[]
     * @throws ValidationException
     */
    protected function validateTrackingOptions(): array
    {
        $trackingOptions = config('short-url.tracking.fields');

        $rules = [
            Rule::make('tracking.default_enabled')->rules(['required', 'boolean']),
        ];

        foreach ($trackingOptions as $trackingOption => $value) {
            $rules[] = Rule::make('tracking.fields.'.$trackingOption)
                ->rules(['required', 'boolean']);
        }

        return $rules;
    }
}
