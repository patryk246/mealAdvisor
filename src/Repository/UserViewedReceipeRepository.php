<?php

namespace App\Repository;

use App\Entity\UserViewedReceipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserViewedReceipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserViewedReceipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserViewedReceipe[]    findAll()
 * @method UserViewedReceipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserViewedReceipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserViewedReceipe::class);
    }

    // /**
    //  * @return UserViewedReceipe[] Returns an array of UserViewedReceipe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserViewedReceipe
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
