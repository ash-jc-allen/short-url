<?php

use AshAllenDesign\ShortURL\Interfaces\UserAgentDriver;

test('interfaces directory contains only interfaces')
    ->expect('AshAllenDesign\ShortURL\Interfaces')
    ->toBeInterfaces();
