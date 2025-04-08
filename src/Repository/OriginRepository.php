<?php

namespace App\Repository;

use App\Entity\Origin;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Origin>
 */
class OriginRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Origin::class);
    }

    public function findAllUsed(): array
    {
        return $this->createQueryBuilder('o')
            ->join('o.recipes', 'r')
            ->groupBy('o.id')
            ->getQuery()
            ->getResult();
    }

    public function findRecipesByOrigin(string $originName): array
    {
        return $this->createQueryBuilder('o')
            ->join('o.recipes', 'r')
            ->where('o.name = :name')
            ->setParameter('name', $originName)
            ->getQuery()
            ->getResult();
    }

    public function findAvailableOrigins(?User $user): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.isPublic = true')
            ->orWhere('o.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
