<?php

namespace App\Repository;

use App\Entity\CoachClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CoachClient>
 *
 * @method CoachClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoachClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoachClient[]    findAll()
 * @method CoachClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoachClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoachClient::class);
    }

//    /**
//     * @return CoachClient[] Returns an array of CoachClient objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CoachClient
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
