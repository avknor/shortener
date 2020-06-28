<?php

namespace Domain\Entities\Link;

use Ramsey\Uuid\Uuid;

class StatUrl
{
    private $statUrl;

    /**
     * @param ShortenedPart $shortenedPart
     */
    public function __construct(ShortenedPart $shortenedPart)
    {
        $this->statUrl = Uuid::uuid5(Uuid::NAMESPACE_URL, (string) $shortenedPart)->toString();
    }

    public function __toString(): string
    {
        return $this->statUrl;
    }

    public function equals(self $originalUrl): bool
    {
        return (string) $this == (string) $originalUrl;
    }
}
