<?php

namespace App\Repository;

use App\Entity\Vehicule;
use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;

/**
 * @extends ServiceEntityRepository<Vehicule>
 */
class VehiculeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicule::class);
    }

    function searchAvailableVehicles(\DateTime $startDate, \DateTime $endDate, string $ville, string $type)
    {

        $qb = $this->createQueryBuilder('v')
            ->leftJoin('v.agence', 'a');

        // Filtrage par ville si fourni
        if (!empty($ville)) {
            $qb->andWhere('a.ville = :ville')
            ->setParameter('ville', $ville);
        }

        // Filtrage par type si fourni
        if (!empty($type)) {
            $qb->andWhere('v.isUtilitaire = :isUtilitaire')
            ->setParameter('isUtilitaire', $type);
        }

        // Exclure les véhicules réservés si dates fournies
        if (!empty($startDate) && !empty($endDate)) {
            $reservedVehicles = $this->getEntityManager()
                ->getRepository(Reservation::class)
                ->createQueryBuilder('r')
                ->select('IDENTITY(r.vehicule)')
                ->where('r.date_debut <= :endDate')
                ->andWhere('r.date_fin >= :startDate')
                ->setParameter('startDate', $startDate)
                ->setParameter('endDate', $endDate)
                ->getQuery()
                ->getScalarResult();

            $reservedVehicleIds = array_column($reservedVehicles, 1);
            if (empty($reservedVehicleIds)) {
                $reservedVehicleIds = [0];
            }

            $qb->andWhere('v.id NOT IN (:reservedVehicles)')
            ->setParameter('reservedVehicles', $reservedVehicleIds, Connection::PARAM_INT_ARRAY);
        }

        return $qb->getQuery()->getResult();

    }

}
