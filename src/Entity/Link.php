<?php

namespace App\Entity;

use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LinkMySQLRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=LinkMySQLRepository::class)
 * @ORM\Table(name="links",indexes={
 *     @ORM\Index(name="byshort_idx", columns={"shortened_part", "active_till"}),
 *     @ORM\Index(name="byoriginal_idx", columns={"original_url", "active_till"}),
 * })
 */
class Link
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid_binary", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $originalUrl;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $shortenedPart;

    /**
     * @ORM\Column(type="uuid_binary")
     */
    private $statUrl;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCommercial;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $activeTill;

    /**
     * @ORM\OneToMany(targetEntity=StatisticsItem::class, mappedBy="link")
     */
    private $statistics;

    public function __construct()
    {
        $this->statistics = new ArrayCollection();
    }

    public function setId(UuidInterface $uuid): self
    {
        $this->id = $uuid;

        return $this;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getOriginalUrl(): ?string
    {
        return $this->originalUrl;
    }

    public function setOriginalUrl(string $originalUrl): self
    {
        $this->originalUrl = $originalUrl;

        return $this;
    }

    public function getShortenedPart(): ?string
    {
        return $this->shortenedPart;
    }

    public function setShortenedPart(string $shortenedPart): self
    {
        $this->shortenedPart = $shortenedPart;

        return $this;
    }

    public function getStatUrl(): ?string
    {
        return $this->statUrl;
    }

    public function setStatUrl(string $statUrl): self
    {
        $this->statUrl = $statUrl;

        return $this;
    }

    public function getIsCommercial(): ?bool
    {
        return $this->isCommercial;
    }

    public function setIsCommercial(bool $isCommercial): self
    {
        $this->isCommercial = $isCommercial;

        return $this;
    }

    public function getActiveTill(): ?\DateTimeImmutable
    {
        return $this->activeTill;
    }

    public function setActiveTill(\DateTimeImmutable $activeTill): self
    {
        $this->activeTill = $activeTill;

        return $this;
    }

    public function addStatistics(StatisticsItem $item): self
    {
        $this->statistics[] = $item;

        return $this;
    }

    public function getStatistics(): Collection
    {
        return $this->statistics;
    }
}
