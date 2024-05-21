<?php

namespace App\Repository;

use App\Entity\Recipes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipes>
 */
class RecipesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipes::class);
    }

    //    /**
    //     * @return Recipes[] Returns an array of Recipes objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recipes
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findRecipesByIdsAndDiets(array $recipeIds, ?array $diets = null): array
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->where('r.id IN (:recipeIds)')
            ->setParameter('recipeIds', $recipeIds);

        if (!empty($diets)) {
            $lowercaseDiets = array_map('strtolower', $diets);
            $queryBuilder->andWhere('LOWER(r.diet) IN (:diets)')
                ->setParameter('diets', $lowercaseDiets);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
