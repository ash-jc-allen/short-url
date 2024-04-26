<?php

declare(strict_types=1);

namespace AshAllenDesign\ShortURL\Classes\UserAgent;

use AshAllenDesign\ShortURL\Interfaces\UserAgentDriver;
use WhichBrowser\Parser;

class ParserPhpDriver implements UserAgentDriver
{
    private Parser $parser;

    public function usingUserAgentString(?string $userAgentString): UserAgentDriver
    {
        $this->parser = new Parser($userAgentString);

        return $this;
    }

    public function getOperatingSystem(): ?string
    {
        if ($this->isRobot()) {
            return null;
        }

        return $this->parser->os->name ?: null;
    }

    public function getOperatingSystemVersion(): ?string
    {
        if ($this->isRobot()) {
            return null;
        }

        return $this->parser->os->getVersion() ?: null;
    }

    public function getBrowser(): ?string
    {
        return $this->parser->browser->getName() ?: null;
    }

    public function getBrowserVersion(): ?string
    {
        return $this->parser->browser->getVersion() ?: null;
    }

    public function getDeviceType(): ?string
    {
        return $this->parser->device->type ?: null;
    }

    public function isDesktop(): bool
    {
        return $this->getDeviceType() === 'desktop';
    }

    public function isMobile(): bool
    {
        return $this->getDeviceType() === 'mobile';
    }

    public function isTablet(): bool
    {
        return $this->getDeviceType() === 'tablet';
    }

    public function isRobot(): bool
    {
        return $this->getDeviceType() === 'bot';
    }
}
