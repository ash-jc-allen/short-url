<?php

use AshAllenDesign\ShortURL\Interfaces\UserAgentDriver;

test('user agent drivers implement the driver interface')
    ->expect('AshAllenDesign\ShortURL\Classes\UserAgent')
    ->classes()
    ->toImplement(UserAgentDriver::class);
