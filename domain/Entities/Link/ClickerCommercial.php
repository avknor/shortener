<?php

declare(strict_types=1);

namespace Domain\Entities\Link;

use Domain\Entities\Link\Service\AdsService;

final class ClickerCommercial implements ClickerInterface
{
    public function click(): StatisticsItem
    {
        return new StatisticsItem(AdsService::nextAdvertise());
    }
}
