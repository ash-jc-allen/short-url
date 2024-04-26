<?php

test('interfaces directory contains only interfaces')
    ->expect('AshAllenDesign\ShortURL\Interfaces')
    ->toBeInterfaces();
