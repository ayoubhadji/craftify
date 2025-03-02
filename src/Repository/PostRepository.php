<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{


    // src/Repository/PostRepository.php


/**
 * @extends ServiceEntityRepository<Post>
 */

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * Get all posts sorted by typePost
     */
    public function findAllSortedByType(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.typePost', 'ASC')  // ✅ Sorting posts by typePost
            ->getQuery()
            ->getResult();
    }
    public function findDistinctTypePosts(): array
{
    return $this->createQueryBuilder('p')
        ->select('DISTINCT p.typePost')
        ->getQuery()
        ->getSingleColumnResult();
}


public function findRecommendedPostsForUser(): array
{
    $entityManager = $this->getEntityManager();

    // Step 1: Get liked typePosts
    $likedTypePostsQuery = $entityManager->createQuery("
        SELECT DISTINCT p.typePost
        FROM App\Entity\Reaction r
        JOIN r.id_post p
        WHERE r.id_user = 1 AND r.type = 'like'
    ");
    $likedTypePosts = array_column($likedTypePostsQuery->getResult(), 'typePost');

    // Step 2: Get liked tranche_dage
    $likedTrancheDageQuery = $entityManager->createQuery("
        SELECT DISTINCT p.tranche_dage
        FROM App\Entity\Reaction r
        JOIN r.id_post p
        WHERE r.id_user = 1 AND r.type = 'like'
    ");
    $likedTrancheDages = array_column($likedTrancheDageQuery->getResult(), 'tranche_dage');

    // Step 3: Get recommended posts based on liked typePost or tranche_dage
    $query = $entityManager->createQuery("
        SELECT p FROM App\Entity\Post p
        WHERE 
            (p.typePost IN (:typePosts) OR p.tranche_dage IN (:trancheDages))
            AND p.id NOT IN (
                SELECT DISTINCT p2.id 
                FROM App\Entity\Reaction r2 
                JOIN r2.id_post p2
                WHERE r2.id_user = 1
            )
    ")
    ->setParameter('typePosts', !empty($likedTypePosts) ? $likedTypePosts : ['dummy']) // Prevent empty array issues
    ->setParameter('trancheDages', !empty($likedTrancheDages) ? $likedTrancheDages : ['dummy']); 

    return $query->getResult();
}



}


    //    /**
    //     * @return Post[] Returns an array of Post objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
