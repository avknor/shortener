<?php

namespace Domain\Entities\Link;

use Ramsey\Uuid\UuidInterface;
use Domain\Entities\Link\Dto\LinkStatisticsDto;

interface StatisticsServiceInterface
{
    public function statistics(UuidInterface $uuid): LinkStatisticsDto;
}
