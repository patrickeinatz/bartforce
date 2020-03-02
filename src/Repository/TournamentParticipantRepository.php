<?php

namespace App\Repository;

use App\Entity\TournamentParticipant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TournamentParticipant|null find($id, $lockMode = null, $lockVersion = null)
 * @method TournamentParticipant|null findOneBy(array $criteria, array $orderBy = null)
 * @method TournamentParticipant[]    findAll()
 * @method TournamentParticipant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TournamentParticipant::class);
    }

    // /**
    //  * @return TournamentParticipant[] Returns an array of TournamentParticipant objects
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
    public function findOneBySomeField($value): ?TournamentParticipants
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
