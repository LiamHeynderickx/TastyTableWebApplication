<?php

namespace App\Repository;

use App\Entity\SavedRecipes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;



/**
 * @extends ServiceEntityRepository<SavedRecipes>
 */
class SavedRecipesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SavedRecipes::class);
    }

    //    /**
    //     * @return SavedRecipes[] Returns an array of SavedRecipes objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?SavedRecipes
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    public function findRecipeIdsByUserAndIsApi(int $userId, int $isApi): array
    {
        $qb = $this->createQueryBuilder('sr')
            ->select('sr.recipeId')
            ->where('sr.userId = :userId')
            ->andWhere('sr.isApi = :isApi')
            ->setParameter('userId', $userId)
            ->setParameter('isApi', $isApi);

        return array_column($qb->getQuery()->getArrayResult(), 'recipeId');
    }
}
