<?php

namespace App\Repository;

use App\Entity\ProjectRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectRoom>
 *
 * @method ProjectRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectRoom[]    findAll()
 * @method ProjectRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectRoom::class);
    }

    public function add(ProjectRoom $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProjectRoom $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Select Rooms by project joint with RoomWalls joint with WallPoints
    public function getRoomsWithWallsAndPointsByProject(int $projectId): array
    {
        $query = $this->createQueryBuilder('r')
            ->select('r', 'rw', 'wp')
            ->join('r.walls', 'rw')
            ->join('rw.points', 'wp')
            ->where('r.project = :projectId')
            ->setParameter('projectId', $projectId)
            ->getQuery();

        return $query->getResult();
    }

//    /**
//     * @return GeometryRoom[] Returns an array of GeometryRoom objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GeometryRoom
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
