<?php

namespace Domain\Entities\Link;

use Domain\Entities\Link\Exception\StringIsNotValidUrl;

class OriginalUrl
{
    private $originalUrl;

    /**
     * @param string $originalUrl
     *
     * @throws StringIsNotValidUrl
     */
    public function __construct(string $originalUrl)
    {
        if (false === filter_var($originalUrl, FILTER_VALIDATE_URL)) {
            throw new StringIsNotValidUrl();
        }

        $this->originalUrl = $originalUrl;
    }

    public function __toString(): string
    {
        return $this->originalUrl;
    }

    public function equals(self $originalUrl): bool
    {
        return (string) $this == (string) $originalUrl;
    }
}
