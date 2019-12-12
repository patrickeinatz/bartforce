<?php

namespace App\Repository;

use App\Entity\TopicContentModule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TopicContentModule|null find($id, $lockMode = null, $lockVersion = null)
 * @method TopicContentModule|null findOneBy(array $criteria, array $orderBy = null)
 * @method TopicContentModule[]    findAll()
 * @method TopicContentModule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicContentModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TopicContentModule::class);
    }

    // /**
    //  * @return TopicContentModule[] Returns an array of TopicContentModule objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TopicContentModule
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
