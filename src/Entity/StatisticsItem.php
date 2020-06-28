<?php

namespace App\Entity;

use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\Mapping as ORM;
use Darsyn\IP\Version\Multi as IP;
use App\Repository\StatisticsItemMySQLRepository;

/**
 * @ORM\Entity(repositoryClass=StatisticsItemMySQLRepository::class)
 * @ORM\Table(name="statistics", indexes={
 *     @ORM\Index(name="summary_idx", columns={"clicked_at", "is_unique"}),
 * })
 */
class StatisticsItem
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid_binary", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $clickedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pictureName;

    /**
     * @ORM\Column(type="ip")
     */
    private $visitorIp;

    /**
     * @ORM\ManyToOne(targetEntity=Link::class, inversedBy="statistics")
     */
    private $link;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isUnique = false;

    public function setId(UuidInterface $uuid): self
    {
        $this->id = $uuid;

        return $this;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getClickedAt(): ?\DateTimeImmutable
    {
        return $this->clickedAt;
    }

    public function setClickedAt(\DateTimeImmutable $clickedAt): self
    {
        $this->clickedAt = $clickedAt;

        return $this;
    }

    public function getPictureName(): ?string
    {
        return $this->pictureName;
    }

    public function setPictureName(?string $pictureName): self
    {
        $this->pictureName = $pictureName;

        return $this;
    }

    public function getVisitorIp(): IP
    {
        return $this->visitorIp;
    }

    public function setIpAddress(IP $ip): self
    {
        $this->visitorIp = $ip;

        return $this;
    }

    public function setIsUnique(bool $isUnique): self
    {
        $this->isUnique = $isUnique;

        return $this;
    }

    public function getIsUnique(): bool
    {
        return $this->isUnique;
    }

    public function setLink(Link $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getLink(): Link
    {
        return $this->link;
    }
}
