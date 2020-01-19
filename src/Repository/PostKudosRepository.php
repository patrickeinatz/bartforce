<?php

namespace App\Repository;

use App\Entity\PostKudos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PostKudos|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostKudos|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostKudos[]    findAll()
 * @method PostKudos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostKudosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostKudos::class);
    }

    // /**
    //  * @return PostKudos[] Returns an array of PostKudos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PostKudos
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
