<?php

namespace App\Repository;

use App\Entity\TournamentMode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TournamentMode|null find($id, $lockMode = null, $lockVersion = null)
 * @method TournamentMode|null findOneBy(array $criteria, array $orderBy = null)
 * @method TournamentMode[]    findAll()
 * @method TournamentMode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentModeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TournamentMode::class);
    }

    // /**
    //  * @return TournamentMode[] Returns an array of TournamentMode objects
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
    public function findOneBySomeField($value): ?TournamentMode
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
