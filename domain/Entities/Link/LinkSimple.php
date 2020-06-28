<?php

namespace Domain\Entities\Link;

class LinkSimple extends Link
{
    public function getClicker(): ClickerInterface
    {
        return new ClickerSimple();
    }
}
