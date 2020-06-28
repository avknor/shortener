<?php


namespace Domain\Entities\Link;


class LinkCommercial extends Link
{

    public function getClicker(): ClickerInterface
    {
        return new ClickerCommercial();
    }
}
