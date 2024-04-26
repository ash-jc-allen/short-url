<?php

use Illuminate\Database\Eloquent\Model;

test('globals')
    ->expect(['dd', 'ddd', 'die', 'dump', 'ray', 'sleep'])
    ->toBeUsedInNothing();

test('all classes use strict types')
    ->expect('AshAllenDesign\ShortURL')
    ->toUseStrictTypes();
