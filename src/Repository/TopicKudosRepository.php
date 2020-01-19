<?php

namespace App\Repository;

use App\Entity\TopicKudos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TopicKudos|null find($id, $lockMode = null, $lockVersion = null)
 * @method TopicKudos|null findOneBy(array $criteria, array $orderBy = null)
 * @method TopicKudos[]    findAll()
 * @method TopicKudos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicKudosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TopicKudos::class);
    }

    // /**
    //  * @return TopicKudos[] Returns an array of TopicKudos objects
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
    public function findOneBySomeField($value): ?TopicKudos
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
