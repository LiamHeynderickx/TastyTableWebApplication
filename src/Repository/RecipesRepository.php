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


    public function findIdsByUserId($userId)
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->select('r.id')
            ->where('r.userId = :userId')
            ->setParameter('userId', $userId);

        return $queryBuilder->getQuery()->getResult();
    }

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

//    public function findRecipesByNameAndDiet($name, ?array $filters = null): array
//    {
//        $queryBuilder = $this->createQueryBuilder('r')
//            ->where('r.recipeName LIKE :name')
//            ->setParameter('name', '%' . $name . '%');
//
//        if (!empty($filters)) {
//            $dietConditions = [];
//            foreach ($filters as $diet => $value) {
//                if ($value === 'true') {
//                    $dietConditions[] = 'r.diet LIKE :'. $diet;
//                    $queryBuilder->setParameter($diet, '%' . $diet . '%');
//                }
//            }
//
//            if (!empty($dietConditions)) {
//                $queryBuilder->andWhere(implode(' OR ', $dietConditions));
//            }
//        }
//
//        return $queryBuilder->getQuery()->getResult();
//    }


    public function findRecipesByName($name): array
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->where('r.recipeName LIKE :name')
            ->setParameter('name', '%' . $name . '%');

        return $queryBuilder->getQuery()->getResult();
    }

}
