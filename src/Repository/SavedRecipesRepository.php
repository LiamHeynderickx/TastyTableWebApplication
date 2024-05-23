<?php

namespace App\Repository;

use App\Entity\SavedRecipes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
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


    public function findRecipeIdsByUserAndIsApi(int $userId, int $isApi,int $isMyRecipe): array
    {
        $qb = $this->createQueryBuilder('sr')
            ->select('sr.recipeId')
            ->where('sr.userId = :userId')
            ->andWhere('sr.isApi = :isApi')
            ->andWhere('sr.isMyRecipe = :isMyRecipe')
            ->setParameter('userId', $userId)
            ->setParameter('isApi', $isApi)
            ->setParameter('isMyRecipe', $isMyRecipe);

        return array_column($qb->getQuery()->getArrayResult(), 'recipeId');
    }
    public function addSavedRecipe(EntityManagerInterface $em,int $userId, int $recipeId, bool $isApi, bool $isMyRecipe): void
    {
        $conn = $em->getConnection();

        $sql = '
            INSERT INTO saved_recipes (user_id, recipe_id, is_api, is_my_recipe) 
            VALUES (:userId, :recipeId, :isApi, :isMyRecipe)
        ';
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery([
            'userId' => $userId,
            'recipeId' => $recipeId,
            'isApi' => $isApi,
            'isMyRecipe' => $isMyRecipe,
        ]);
    }
}
