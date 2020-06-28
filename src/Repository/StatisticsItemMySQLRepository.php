<?php

namespace App\Repository;

use DateTime;
use DateTimeImmutable;
use App\Entity\StatisticsItem;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method StatisticsItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatisticsItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatisticsItem[]    findAll()
 * @method StatisticsItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatisticsItemMySQLRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatisticsItem::class);
    }

    public function summary()
    {
        $qb = $this->createQueryBuilder('si')
            ->select('l.shortenedPart, l.originalUrl, COUNT(l.shortenedPart) AS unique_visits, l.isCommercial, l.statUrl')
            ->andWhere('si.isUnique = true')
            ->andWhere('si.clickedAt between :ago and :now')
            ->setParameter('now', new DateTimeImmutable('now'))
            ->setParameter('ago', new DateTimeImmutable('-14 days'))
            ->leftJoin('si.link', 'l')
            ->groupBy('l.shortenedPart')
            ->getQuery();

        return $qb->getArrayResult();
    }
}
