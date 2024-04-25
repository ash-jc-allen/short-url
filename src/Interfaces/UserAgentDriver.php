<?php

declare(strict_types=1);

namespace AshAllenDesign\ShortURL\Interfaces;

interface UserAgentDriver
{
    public function usingUserAgentString(?string $userAgentString): self;

    public function getOperatingSystem(): ?string;

    public function getOperatingSystemVersion(): ?string;

    public function getBrowser(): ?string;

    public function getBrowserVersion(): ?string;

    public function getDeviceType(): ?string;

    public function isDesktop(): bool;

    public function isMobile(): bool;

    public function isTablet(): bool;

    public function isRobot(): bool;
}
