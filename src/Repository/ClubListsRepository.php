<?php

namespace App\Repository;

use App\Entity\ClubLists;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClubLists|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClubLists|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClubLists[]    findAll()
 * @method ClubLists[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClubListsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClubLists::class);
    }

    // /**
    //  * @return ClubLists[] Returns an array of ClubLists objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ClubLists
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
