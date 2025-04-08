<?php

namespace App\Repository;

use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function findAllWithRecipeCount(): array
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.recipes', 'r')
            ->addSelect('COUNT(r.id) AS HIDDEN recipeCount')
            ->groupBy('t.id')
            ->orderBy('recipeCount', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByLabel(string $label): ?Tag
    {
        return $this->createQueryBuilder('t')
            ->where('t.label = :label')
            ->setParameter('label', $label)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAvailableTags(?User $user): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.isPublic = true')
            ->orWhere('t.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
