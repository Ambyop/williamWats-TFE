<?php

namespace App\Repository;

use App\Entity\MatchCancellation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MatchCancellation|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatchCancellation|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatchCancellation[]    findAll()
 * @method MatchCancellation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchCancellationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatchCancellation::class);
    }

    // /**
    //  * @return MatchCancellation[] Returns an array of MatchCancellation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MatchCancellation
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
