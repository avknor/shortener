<?php

declare(strict_types=1);

namespace Domain\Entities\Link;

use Ramsey\Uuid\Uuid;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use Darsyn\IP\Version\Multi as IP;
use Darsyn\IP\Exception\WrongVersionException;
use Darsyn\IP\Exception\InvalidIpAddressException;
use Domain\Entities\Link\Service\StatisticsItemService;

class StatisticsItem
{
    private $id;

    private $datetime;

    private $pictureName;

    private $visitorIp;

    /**
     * @var bool is visitor unique or not
     */
    private $isUnique = false;

    /**
     * @param string|null $pictureName
     *
     * @throws InvalidIpAddressException
     * @throws WrongVersionException
     */
    public function __construct(string $pictureName = null)
    {
        $this->id = Uuid::uuid4();
        $this->datetime = new DateTimeImmutable();
        $this->pictureName = $pictureName;

        $this->visitorIp = IP::factory(StatisticsItemService::getVisitorHostIp());
    }

    /**
     * @return UuidInterface
     */
    public function id(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return DateTimeImmutable
     */
    public function clickedAt(): DateTimeImmutable
    {
        return $this->datetime;
    }

    /**
     * @return string|null
     */
    public function pictureName(): ?string
    {
        return $this->pictureName;
    }

    public function visitorIp(): IP
    {
        return $this->visitorIp;
    }

    public function makeUnique(): self
    {
        $this->isUnique = true;

        return $this;
    }

    public function isUnique(): bool
    {
        return $this->isUnique;
    }
}
