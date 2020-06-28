<?php

declare(strict_types=1);

namespace Domain\Entities\Link;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Id
{
    private $id;

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public function __toString(): string
    {
        return $this->uuid()->toString();
    }

    public static function next(): self
    {
        return new self(Uuid::uuid4());
    }

    public function uuid(): UuidInterface
    {
        return $this->id;
    }

    public function equalsTo(self $other): bool
    {
        return (string) $this === (string) $other;
    }
}
