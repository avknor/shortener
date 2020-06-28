<?php

declare(strict_types=1);

namespace Domain\Entities\Link;

use DateTimeImmutable;

class LinkMeta
{
    private $activeTill;

    private $isCommercial;

    public function __construct(DateTimeImmutable $activeTill, bool $isCommercial = false)
    {
        $this->activeTill = $activeTill;
        $this->isCommercial = $isCommercial;
    }

    public function activeTill(): DateTimeImmutable
    {
        return $this->activeTill;
    }

    public function isCommercial(): bool
    {
        return $this->isCommercial;
    }
}
