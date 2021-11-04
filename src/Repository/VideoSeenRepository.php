<?php

namespace App\Repository;

use App\Entity\VideoSeen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VideoSeen|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoSeen|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoSeen[]    findAll()
 * @method VideoSeen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoSeenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoSeen::class);
    }

    // /**
    //  * @return VideoSeen[] Returns an array of VideoSeen objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VideoSeen
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
