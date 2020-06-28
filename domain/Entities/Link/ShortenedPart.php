<?php

namespace Domain\Entities\Link;

use Ramsey\Uuid\Uuid;
use Domain\Entities\Link\Exception\WrongShortenedPartFormat;

class ShortenedPart
{
    private $shortenedPart;

    /**
     * @param string $shortenedPart
     *
     * @throws WrongShortenedPartFormat
     */
    public function __construct(string $shortenedPart = null)
    {
        if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $shortenedPart) && !is_null($shortenedPart)) {
            throw new WrongShortenedPartFormat();
        }

        $this->shortenedPart = $shortenedPart ?? self::next();
    }

    /**
     * @throws WrongShortenedPartFormat
     *
     * @return ShortenedPart
     *
     * @todo improve shortenUrl algorithm
     */
    public static function next(): self
    {
        return new self(hash('crc32b', Uuid::uuid4()));
    }

    public function __toString(): string
    {
        return $this->shortenedPart;
    }

    public function equals(self $shortenedPart): bool
    {
        return (string) $this == (string) $shortenedPart;
    }
}
