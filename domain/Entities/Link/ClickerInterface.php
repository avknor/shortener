<?php

namespace Domain\Entities\Link;

interface ClickerInterface
{
    public function click(): StatisticsItem;
}
