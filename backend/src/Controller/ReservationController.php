<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Agence;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class ReservationController extends AbstractController
{
    #[Route('api/reservation', name: 'api_add_reservation', methods: ['POST'])]
    public function addReservation(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = $request->request->all();


        return $this->json($request->request->all());

        $vehicule = new Reservation();
        // $vehicule->setMarque($data['marque']);
        // $vehicule->setModele($data['modele']);
        // $vehicule->setNbPorte($data['nbPorte']);
        // $vehicule->setCouleur($data['couleur']);
        // $vehicule->setPhotoPresentation(null);
        // $vehicule->setKilometrage($data['kilometrage']);
        // $vehicule->setPresentationVehicule($data['presentationVehicule'] ?? null);
        // $vehicule->setRemarque($data['remarque'] ?? null);
        // $vehicule->setPhotoVehicule([]);
        
        $agence = $em->getRepository(Agence::class)->find($data['agence']);
        if (!$agence) {
            return $this->json(['message' => 'Agence non trouvée'], 404);
        }
        
        $vehicule->setAgence($agence);

        $em->persist($vehicule);
        $em->flush();

        return $this->json([
            'result' => 'success',
            'message' => 'Reservation ajoutée avec succès',
        ]);
    }
}
