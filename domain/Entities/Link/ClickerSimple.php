<?php

declare(strict_types=1);

namespace Domain\Entities\Link;

final class ClickerSimple implements ClickerInterface
{
    public function click(): StatisticsItem
    {
        return new StatisticsItem();
    }
}
