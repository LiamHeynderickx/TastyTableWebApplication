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

    public function getFilteredRecipes(?array $filters = null): array
    {
        $queryBuilder = $this->createQueryBuilder('r');

        $diets = [];

        if($filters['vegetarian'] == 'on'){
            $diets[] = 'vegetarian';
        }
        if($filters['vegan'] == 'on'){
            $diets[] = 'vegan';
        }
        if($filters['gluten-free'] == 'on'){
            $diets[] = 'gluten free';
        }
        if($filters['dairy-free'] == 'on'){
            $diets[] = 'dairy free';
        }

        $queryBuilder->andWhere('LOWER(r.diet) IN (:diets)')
            ->setParameter('diets', $diets);

        return $queryBuilder->getQuery()->getResult();
    }


//    public function getFilteredRecipes(?array $filters = null): array
//    {
//        $queryBuilder = $this->createQueryBuilder('r');
//
//        if (!empty($filters)) {
//            $dietConditions = [];
//            $parameters = [];
//            $index = 0;
//
//            foreach ($filters as $diet => $value) {
//                if ($value === 'true') {
//                    $dietConditions[] = 'r.diet LIKE :diet' . $index;
//                    $parameters['diet' . $index] = '%' . $diet . '%';
//                    $index++;
//                }
//            }
//
//            if (!empty($dietConditions)) {
//                $queryBuilder->where(implode(' OR ', $dietConditions));
//                foreach ($parameters as $key => $value) {
//                    $queryBuilder->setParameter($key, $value);
//                }
//            }
//        }
//
//        return $queryBuilder->getQuery()->getResult();
//    }

//    public function getFilteredRecipes(?array $filters = null): array
//    {
//        $queryBuilder = $this->createQueryBuilder('r');
//
//        if (!empty($filters)) {
//            $dietConditions = [];
//            $parameters = [];
//            $index = 0;
//
//            foreach ($filters as $diet => $value) {
//                if ($value === 'true') {
//                    // Replace dashes with underscores in the parameter name
//                    $sanitizedDiet = str_replace('-', '_', $diet);
//                    $dietConditions[] = 'r.diet LIKE :diet' . $index;
//                    $parameters['diet' . $index] = '%' . $diet . '%';
//                    $index++;
//                }
//            }
//
//            if (!empty($dietConditions)) {
//                $queryBuilder->where(implode(' OR ', $dietConditions));
//                foreach ($parameters as $key => $value) {
//                    $queryBuilder->setParameter($key, $value);
//                }
//            }
//        }
//
//        return $queryBuilder->getQuery()->getResult();
//    }

}
