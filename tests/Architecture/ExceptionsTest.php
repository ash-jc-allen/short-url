<?php

test('exceptions extend the base exception class')
    ->expect('AshAllenDesign\ShortURL\Exceptions')
    ->classes()
    ->toExtend(Exception::class);
