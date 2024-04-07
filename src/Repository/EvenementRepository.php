<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }
    public function searchByName($searchQuery,$page, $pageSize): Paginator
{
    $query =  $this->createQueryBuilder('e')
        ->andWhere('e.nomev LIKE :searchQuery')
        ->setParameter('searchQuery', '%' . $searchQuery . '%')
        ->getQuery();
        // ->getResult();
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
            ->orderBy('e.idevent', 'ASC') // Order by the correct identifier field
            ->getQuery(); 

        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize);

        return $paginator;
    }
    public function paginateByCategory(string $category, int $page, int $pageSize): Paginator
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.genreevenement = :category')
            ->setParameter('category', $category)
            ->orderBy('e.idevent', 'DESC');

        $query = $qb->getQuery();

        // Paginate the results
        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize);

        return $paginator;
    }    
   
    public function countEventsByCategory(): array
    {
        return $this->createQueryBuilder('e')
            ->select('e.genreevenement, COUNT(e.idevent) as count')
            ->groupBy('e.genreevenement')
            ->getQuery()
            ->getResult();
    }
}
