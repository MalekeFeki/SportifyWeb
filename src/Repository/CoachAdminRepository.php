<?php

// CoachAdminRepository.php

namespace App\Repository;

use App\Entity\CoachAdmin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CoachAdminRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoachAdmin::class);
    }

    /**
     * Retrieves all CoachAdmin entities with their associated photos.
     *
     * @return array
     */
    public function findAllWithPhotos(): array
    {
        return $this->createQueryBuilder('ca')
            ->getQuery()
            ->getResult();
    }
    public function findByFirstLetter($letter)
{
    return $this->createQueryBuilder('c')
        ->andWhere('c.nom LIKE :letter OR c.prenom LIKE :letter')
        ->setParameter('letter', $letter.'%')
        ->orderBy('c.nom', 'ASC')
        ->getQuery()
        ->getResult();
}

}
