<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Agence;
use App\Entity\Vehicule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class ReservationController extends AbstractController
{
    #[Route('api/reservation', name: 'api_add_reservation', methods: ['POST'])]
    public function addReservation(Request $request, EntityManagerInterface $em, Security $security): JsonResponse
    {

        $user = $security->getUser(); // Récupère l'utilisateur actuellement authentifié
        if(!$user){
            return $this->json(['error' => 'Utilisateur non authentifié.'], 401);
        }

        $data = $request->request->all();

        $reservation = new Reservation();
        $reservation->setDateDebut(new \DateTime($data['startDate']));
        $reservation->setDateFin(new \DateTime($data['endDate']));

        // On récupère le véhicule avec l'id
        $vehiculeId = $data['vehiculeId'];
        $vehicule = $em->getRepository(Vehicule::class)->find($vehiculeId);

        if (!$vehicule) {
            return $this->json(['error' => 'Véhicule non trouvé.'], 404);
        }

        if(empty($data['priceDetails']) || !is_array($data['priceDetails']) || !isset($data['totalPrice'])){
            return $this->json(['error' => 'Détails de prix invalides.'], 404);
        }

        $reservation->setVehicule($vehicule);
        $reservation->setUser($user);
        $reservation->setStatut('En attente');
        $reservation->setRemarque($data['remarque'] ?? null);
        $reservation->setDetailReservation($data['priceDetails'] ?? []);
        $reservation->setPrixTotal($data['totalPrice'] ?? 0.0);

        $em->persist($reservation);
        $em->flush();

        return $this->json([
            'result' => 'success',
            'message' => 'Reservation ajoutée avec succès',
        ]);
    }
}
