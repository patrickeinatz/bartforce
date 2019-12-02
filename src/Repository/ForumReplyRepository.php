<?php

namespace App\Repository;

use App\Entity\ForumReply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ForumReply|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumReply|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumReply[]    findAll()
 * @method ForumReply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumReplyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumReply::class);
    }

    // /**
    //  * @return ForumReply[] Returns an array of ForumReply objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ForumReply
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
