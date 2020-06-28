<?php

namespace App\Service;

use App\Repository\StatisticsItemMySQLRepository;
use Ramsey\Uuid\UuidInterface;
use App\Exception\LinkNotFound;
use App\Repository\LinkMySQLRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\ConversionException;
use Domain\Entities\Link\Dto\LinkStatisticsDto;
use Domain\Entities\Link\StatisticsServiceInterface;
use Domain\Entities\Link\Exception\StringIsNotValidUrl;
use Domain\Entities\Link\Exception\WrongShortenedPartFormat;

class StatisticsService implements StatisticsServiceInterface
{
    private $linkRepository;

    private $repository;

    public function __construct(LinkMySQLRepository $linkRepository, StatisticsItemMySQLRepository $repository)
    {
        $this->linkRepository = $linkRepository;
        $this->repository = $repository;
    }

    /**
     * @param UuidInterface $uuid
     *
     * @throws ConversionException
     * @throws LinkNotFound
     * @throws NonUniqueResultException
     * @throws StringIsNotValidUrl
     * @throws WrongShortenedPartFormat
     *
     * @return LinkStatisticsDto
     */
    public function statistics(UuidInterface $uuid): LinkStatisticsDto
    {
        $linkEntity = $this->linkRepository->findByStatisticsUuid($uuid);
        $link = LinkService::mapToDomainLink($linkEntity);

        return new LinkStatisticsDto(
            (string) $link->url()->originalUrl(),
            (string) $link->url()->shortenedPart(),
            $link->meta()->activeTill(),
            $link->meta()->isCommercial(),
            $linkEntity->getStatistics()->toArray(),
            self::pivot($linkEntity->getStatistics())
        );
    }

    public static function pivot(Collection $statistics): array
    {
        $pivot = $statistics->map(function ($x) {
            return $x->getPictureName();
        });

        $pivot = array_filter($pivot->toArray());

        return array_count_values($pivot);
    }

    public function summary()
    {
        return $this->repository->summary();
    }
}
