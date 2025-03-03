<?php

namespace App\Repository;

use App\Entity\Expedition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Expedition>
 */
class ExpeditionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expedition::class);
    }

    //    /**
    //     * @return Expedition[] Returns an array of Expedition objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Expedition
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    // src/Repository/ExpeditionRepository.php
public function findByFilters(?string $titre, ?string $aventurierId, ?string $objectif)
{
    $queryBuilder = $this->createQueryBuilder('e');

    // Filtrer par titre
    if ($titre) {
        $queryBuilder->andWhere('e.titre LIKE :titre')
                     ->setParameter('titre', '%' . $titre . '%');
    }

    // Filtrer par aventurier
    if ($aventurierId) {
        $queryBuilder->andWhere('e.aventurier = :aventurier')
                     ->setParameter('aventurier', $aventurierId);
    }

    // Filtrer par objectif
    if ($objectif) {
        $queryBuilder->andWhere('e.objectif LIKE :objectif')
                     ->setParameter('objectif', '%' . $objectif . '%');
    }

    return $queryBuilder->getQuery()->getResult();
}

}
