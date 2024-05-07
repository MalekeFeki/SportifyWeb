<?php

namespace App\Repository;

use App\Entity\Seance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class SeanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Seance::class);
    }

    // public function countSeancesBySalle(Salle $salle): int
    // {
    //     return $this->createQueryBuilder('s')
    //         ->select('COUNT(s.idseance)')
    //         ->where('s.idS = :salle') // Use the association property instead of getIdS
    //         ->setParameter('salle', $salle)
    //         ->getQuery()
    //         ->getSingleScalarResult();
    // }
}
