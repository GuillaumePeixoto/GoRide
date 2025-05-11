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

        /* 
            Requete SQL:
            SELECT v.*
            FROM vehicule v
            LEFT JOIN agence a ON v.agence_id = a.id
            WHERE a.ville = :ville
            AND v.isUtilitaire = :isUtilitaire
            AND v.id NOT IN (
                SELECT r.vehicule_id
                FROM reservation r
                WHERE (r.dateDebut <= :endDate AND r.dateFin >= :startDate)
            )
            ORDER BY v.id DESC
        */

        $reservedVehicles = $this->getEntityManager()
            ->getRepository(Reservation::class)
            ->createQueryBuilder('r')
            ->select('v.id') // ou juste 'v' selon ce que tu veux
            ->innerJoin('r.vehicule', 'v')
            ->where('r.date_debut <= :endDate')
            ->andWhere('r.date_fin >= :startDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();


        $reservedVehicleIds = array_map(fn($item) => $item['vehicule']->getId(), $reservedVehicles);
        if (empty($reservedVehicleIds)) {
            $reservedVehicleIds = [0];
        }

        // Étape 2 : Requête principale pour les véhicules non réservés
        $queryBuilder = $this->createQueryBuilder('v')
            ->leftJoin('v.agence', 'a')
            ->where('a.ville = :ville')
            ->andWhere('v.isUtilitaire = :isUtilitaire')
            ->andWhere('v.id NOT IN (:reservedVehicles)')
            ->setParameter('ville', $ville)
            ->setParameter('isUtilitaire', $type)
            ->setParameter('reservedVehicles', $reservedVehicleIds, Connection::PARAM_INT_ARRAY);

        return $queryBuilder->getQuery()->getResult();
    }

    //    /**
    //     * @return Vehicule[] Returns an array of Vehicule objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Vehicule
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
