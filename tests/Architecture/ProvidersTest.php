<?php

test('providers extend the base provider class')
    ->expect('AshAllenDesign\ShortURL\Providers')
    ->classes()
    ->toExtend(\Illuminate\Support\ServiceProvider::class);
