<?php

namespace App\Repository;

use App\Entity\Salle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

class SalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salle::class);
    }


    // public function calculateTotalSeancesForSalles(): array
    // {
    //     return $this->createQueryBuilder('s')
    //         ->select('s.idS, COUNT(se) as totalSeances')
    //         ->leftJoin('s.seances', 'se')
    //         ->groupBy('s.idS')
    //         ->getQuery()
    //         ->getResult();
    // }

    public function searchByName($searchQuery, $page, $pageSize): Paginator
    {
        $query = $this->createQueryBuilder('e')
            ->andWhere('e.nom LIKE :searchQuery')
            ->setParameter('searchQuery', '%' . $searchQuery . '%')
            ->getQuery();

        // Paginate the results
        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize);

        return $paginator;
    }
    // You can add custom repository methods here
    public function paginate($page, $pageSize)
    {

        $query = $this->createQueryBuilder('e')
            ->getQuery();

        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize);

        return $paginator;
    }

    
}
