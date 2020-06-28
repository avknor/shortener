<?php

declare(strict_types=1);

namespace Domain\Entities\Link;

abstract class Link
{
    protected $id;

    protected $url;

    protected $meta;

    protected $statistics = [];

    abstract public function getClicker(): ClickerInterface;

    public function __construct(Id $id, Url $url, LinkMeta $meta)
    {
        $this->id = $id;
        $this->url = $url;
        $this->meta = $meta;
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function url(): Url
    {
        return $this->url;
    }

    public function meta(): LinkMeta
    {
        return $this->meta;
    }

    public function statistics(): array
    {
        return $this->statistics;
    }

    public function isCommercial(): bool
    {
        return $this->meta->isCommercial();
    }

    public function click(bool $uniqueVisit = false): StatisticsItem
    {
        $clicker = $this->getClicker();
        $statisticsItem = $clicker->click();

        if (true === $uniqueVisit) {
            $statisticsItem->makeUnique();
        }
        $this->statistics[] = $statisticsItem;

        return $statisticsItem;
    }
}
