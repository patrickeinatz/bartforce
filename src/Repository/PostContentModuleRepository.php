<?php

namespace App\Repository;

use App\Entity\PostContentModule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PostContentModule|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostContentModule|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostContentModule[]    findAll()
 * @method PostContentModule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostContentModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostContentModule::class);
    }

    // /**
    //  * @return PostContentModule[] Returns an array of PostContentModule objects
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
    public function findOneBySomeField($value): ?PostContentModule
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
