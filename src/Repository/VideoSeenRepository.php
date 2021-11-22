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

    public function viewCounter($video_id)
    {
        return $this->createQueryBuilder('video_seen')
            ->select('count(video_seen.id)')
            ->where('video_seen.video = :video_id')
            ->setParameter('video_id', $video_id)
            ->getQuery()
            ->getResult()
        ;
    }

}
