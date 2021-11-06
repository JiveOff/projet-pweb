<?php

namespace App\Repository;

use App\Entity\Facture;
use App\Entity\Vehicule;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Vehicule|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicule|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicule[]    findAll()
 * @method Vehicule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehiculeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicule::class);
    }


    /**
     * @return Vehicule[]
     */
    public function findAllAvailable($ordre = "asc"): array
    {
        if($ordre == "asc" || $ordre == "desc") {
            $entityManager = $this->getEntityManager();
        
            $query = $entityManager->createQuery(
                'SELECT v
                FROM App\Entity\Vehicule v
                WHERE v.quantite > 0 AND v.disponible = 1
                ORDER BY v.prix ' . $ordre
            );

            return $query->getResult();
        } else {
            return [];
        }
    }

}
