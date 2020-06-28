<?php

namespace App\Repository;

use DateTimeImmutable;
use App\Entity\StatisticsItem;
use Doctrine\ORM\ORMException;
use App\Entity\Link as ORMLink;
use App\Exception\LinkNotFound;
use Doctrine\ORM\NoResultException;
use Ramsey\Uuid\Doctrine\UuidBinaryType;
use App\Exception\NonUniqueShortenedPart;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\ORM\NonUniqueResultException;
use Domain\Entities\Link\Dto\LinkClickDto;
use Doctrine\DBAL\Types\ConversionException;
use Domain\Entities\Link\Link as DomainLink;
use Domain\Entities\Link\LinkRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ORMLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method ORMLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method ORMLink[]    findAll()
 * @method ORMLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkMySQLRepository extends ServiceEntityRepository implements LinkRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMLink::class);
    }

    /**
     * @param DomainLink $link
     *
     * @throws ORMException
     * @throws NonUniqueShortenedPart
     *
     * @todo: refactor! Repository can't access directly to domain layer
     */
    public function add(DomainLink $link)
    {
        $this->proveUniqueness($link->url()->shortenedPart());

        $newLink = new ORMLink();
        $newLink
            ->setId($link->id()->uuid())
            ->setOriginalUrl($link->url()->originalUrl())
            ->setShortenedPart($link->url()->shortenedPart())
            ->setStatUrl($link->url()->statisticsUrl())
            ->setIsCommercial($link->meta()->isCommercial())
            ->setActiveTill($link->meta()->activeTill());

        $em = $this->getEntityManager();
        $em->persist($newLink);
        $em->flush();
    }

    /**
     * @param LinkClickDto $dto
     *
     * @throws LinkNotFound
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return void Original URL to redirect
     */
    public function click(LinkClickDto $dto): void
    {
        $link = $this->findByShortenedPart($dto->shortenedPart, true);

        $statisticsItem = new StatisticsItem();
        $statisticsItem
            ->setId($dto->statisticsItemId)
            ->setClickedAt($dto->clickedAt)
            ->setPictureName($dto->picture)
            ->setIpAddress($dto->visitorIp)
            ->setIsUnique($dto->isUnique)
            ->setLink($link);

        $link->addStatistics($statisticsItem);

        $em = $this->getEntityManager();
        $em->persist($statisticsItem);
        $em->persist($link);
        $em->flush();
    }

    /**
     * @param string $shortenedPart
     * @param bool   $onlyActive
     *
     * @throws LinkNotFound
     * @throws NonUniqueResultException
     *
     * @return ORMLink
     */
    public function findByShortenedPart(string $shortenedPart, bool $onlyActive = true): ORMLink
    {
        $qb = $this
            ->createQueryBuilder('l')
            ->andWhere('l.shortenedPart = :short')
            ->setParameter('short', $shortenedPart);

        if ($onlyActive) {
            $qb
                ->andWhere('l.activeTill > :till')
                ->setParameter('till', new DateTimeImmutable());
        }

        $linkEntity = $qb
            ->getQuery()
            ->getOneOrNullResult();

        if (is_null($linkEntity)) {
            throw new LinkNotFound();
        }

        return $linkEntity;
    }

    /**
     * @param string $uuid
     *
     * @throws ConversionException
     * @throws LinkNotFound
     * @throws NonUniqueResultException
     *
     * @return ORMLink
     */
    public function findByStatisticsUuid(string $uuid): ORMLink
    {
        $uuidBinary = new UuidBinaryType();

        $qb = $this
            ->createQueryBuilder('l')
            ->andWhere('l.statUrl = :uuid')
            ->setParameter('uuid', $uuidBinary->convertToDatabaseValue($uuid, new MySqlPlatform()));

        $linkEntity = $qb
            ->getQuery()
            ->getOneOrNullResult();

        if (is_null($linkEntity)) {
            throw new LinkNotFound();
        }

        return $linkEntity;
    }

    /**
     * @param string $shortenedPart
     *
     * @throws NoResultException
     * @throws NonUniqueShortenedPart
     * @throws NonUniqueResultException
     *
     * @todo Should this method be a part of Repository? Think to refactor it as static(?) method of LinkService.
     *
     * @return bool
     */
    private function proveUniqueness(string $shortenedPart): bool
    {
        $qb = $this->createQueryBuilder('l')
            ->select('count(l.id)')
            ->andWhere('l.shortenedPart = :short')
            ->setParameter('short', $shortenedPart)
            ->getQuery();

        if ($qb->getSingleScalarResult() != 0) {
            throw new NonUniqueShortenedPart();
        }

        return true;
    }
}
