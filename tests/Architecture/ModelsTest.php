<?php

use Illuminate\Database\Eloquent\Model;

test('models extends base model')
    ->expect('AshAllenDesign\ShortURL\Models')
    ->classes()
    ->toExtend(Model::class)
    ->ignoring('AshAllenDesign\ShortURL\Models\Factories');

test('model factories extend the base factory class')
    ->expect('AshAllenDesign\ShortURL\Models\Factories')
    ->classes()
    ->toExtend('Illuminate\Database\Eloquent\Factories\Factory');
