<?php

namespace Domain\Entities\Link\Dto;

use DateTimeImmutable;

class LinkStatisticsDto
{
    public $originalUrl;

    public $shortenedPart;

    public $activeTill;

    public $isCommercial;

    public $statistics = [];

    public $pivot;

    public function __construct(string $originalUrl,
                                string $shortenedPart,
                                DateTimeImmutable $activeTill,
                                bool $isCommercial,
                                array $statistics,
                                array $pivot)
    {
        $this->originalUrl = $originalUrl;
        $this->shortenedPart = $shortenedPart;
        $this->activeTill = $activeTill;
        $this->isCommercial = $isCommercial;
        $this->statistics = $statistics;
        $this->pivot = $pivot;
    }
}
