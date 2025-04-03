<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function findLatest(int $limit = 10): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByTag(string $tagLabel): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.tags', 't')
            ->where('t.label = :label')
            ->setParameter('label', $tagLabel)
            ->getQuery()
            ->getResult();
    }

    public function searchByTitle(string $query): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.title LIKE :q')
            ->setParameter('q', '%' . $query . '%')
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

}
