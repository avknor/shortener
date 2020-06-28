<?php

namespace Domain\Entities\Link\Dto;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use Darsyn\IP\Version\Multi as IP;

class LinkClickDto
{
    public $shortenedPart;

    public $clickedAt;

    public $statisticsItemId;

    public $visitorIp;

    public $picture;

    public $isUnique;

    public function __construct(string $shortenedPart, DateTimeImmutable $clickedAt, UuidInterface $statisticsItemId, IP $visitorIp, bool $isUnique = false, string $picture = null)
    {
        $this->shortenedPart = $shortenedPart;
        $this->picture = $picture;
        $this->clickedAt = $clickedAt;
        $this->visitorIp = $visitorIp;
        $this->isUnique = $isUnique;
        $this->statisticsItemId = $statisticsItemId;
    }
}
