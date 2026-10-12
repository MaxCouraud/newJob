<?php

namespace App\Repository;

use App\Entity\Visit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class VisitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visit::class);
    }

    /** @return array<string, mixed> */
    public function getStatsByProfile(): array
    {
        return $this->createQueryBuilder('v')
            ->select('v.profileType, COUNT(v.id) as visits, MAX(v.createdAt) as lastVisit')
            ->groupBy('v.profileType')
            ->orderBy('visits', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }

    /** @return Visit[] */
    public function findRecent(int $limit = 50): array
    {
        return $this->createQueryBuilder('v')
            ->orderBy('v.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /** @return array<string, mixed> */
    public function getStatsByCode(): array
    {
        return $this->createQueryBuilder('v')
            ->select('v.code, v.profileType, COUNT(v.id) as visits, MAX(v.createdAt) as lastVisit')
            ->groupBy('v.code')
            ->orderBy('visits', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }
}
