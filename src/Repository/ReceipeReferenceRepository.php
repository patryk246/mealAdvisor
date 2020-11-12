<?php

namespace App\Repository;

use App\Entity\ReceipeReference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReceipeReference|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReceipeReference|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReceipeReference[]    findAll()
 * @method ReceipeReference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReceipeReferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReceipeReference::class);
    }

    // /**
    //  * @return ReceipeReference[] Returns an array of ReceipeReference objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReceipeReference
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
