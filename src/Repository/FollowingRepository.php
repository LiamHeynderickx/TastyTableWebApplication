<?php

namespace App\Repository;

use App\Entity\Following;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Following>
 */
class FollowingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Following::class);
    }

    //    /**
    //     * @return Following[] Returns an array of Following objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Following
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findFollowingByUser(int $userId)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.userId = :userId') //Not sure if this works
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    public function removeFollowingByUserAndFollowing(int $userId, int $followingId): void
    {
        $qb = $this->createQueryBuilder('f')
            ->delete()
            ->where('f.userId = :userId')
            ->andWhere('f.followingId = :followingId')
            ->setParameter('userId', $userId)
            ->setParameter('followingId', $followingId)
            ->getQuery();

        $qb->execute();
    }
}
