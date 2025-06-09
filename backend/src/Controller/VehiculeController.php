<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Entity\Agence;
use App\Repository\VehiculeRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\AllowAnonymous;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\FileHelper;


class VehiculeController extends AbstractController
{
    #[Route('/api/vehicules', methods: ['GET'])]
    public function list(VehiculeRepository $vehiculeRepository): JsonResponse
    {
        $vehicules = $vehiculeRepository->findAll();
        return $this->json($vehicules, 200, [], ['groups' => 'vehicule:read']);
    }

    #[Route('/api/vehicules', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): JsonResponse
    {
        // $data = json_decode($request->getContent(), true);
        $data = $request->request->all();

        $vehicule = new Vehicule();
        $vehicule->setMarque($data['marque']);
        $vehicule->setModele($data['modele']);
        $vehicule->setNbPorte($data['nbPorte']);
        $vehicule->setCouleur($data['couleur']);
        $vehicule->setPhotoPresentation(null);
        $vehicule->setKilometrage($data['kilometrage']);
        $vehicule->setPresentationVehicule($data['presentationVehicule'] ?? null);
        $vehicule->setRemarque($data['remarque'] ?? null);
        $vehicule->setPhotoVehicule([]);

        if($data['isUtilitaire']){
            $vehicule->setTypeVehicule('Utilitaire');
            $vehicule->setIsUtilitaire(1);
        } else {
            $vehicule->setTypeVehicule($data['typeVehicule'] ?? '');
            $vehicule->setIsUtilitaire(0);
        }
        
        $agence = $em->getRepository(Agence::class)->find($data['agence']);
        if (!$agence) {
            return $this->json(['message' => 'Agence non trouvée'], 404);
        }
        
        $vehicule->setAgence($agence);

        $photoPresentation = $request->files->get('photoPresentation');
        if ($photoPresentation) {
            $newFilename = FileHelper::getHashedFileName($photoPresentation);

            $photoPresentation->move($this->getParameter('uploads_directory') . '/vehicules', $newFilename);
            $vehicule->setPhotoPresentation('/uploads/vehicules/' . $newFilename);
        }

        // Gestion des photos multiples
        $photoVehiculeFiles = $request->files->all('photoVehicule');
        $photoVehiculesPaths = [];
        if ($photoVehiculeFiles) {
            foreach ($photoVehiculeFiles as $photoFile) {
                $newFilename = FileHelper::getHashedFileName($photoFile);

                $photoFile->move($this->getParameter('uploads_directory') . '/vehicules', $newFilename);
                $photoVehiculesPaths[] = '/uploads/vehicules/' . $newFilename;
            }
            $vehicule->setPhotoVehicule($photoVehiculesPaths);
        }


        $em->persist($vehicule);
        $em->flush();

        return $this->json($vehicule, 201, [], ['groups' => 'vehicule:read']);
    }

    #[Route('/api/vehicule/{id}', name: 'api_vehicule_get', methods: ['GET'])]
    public function show(Vehicule $vehicule): JsonResponse
    {
        return $this->json($vehicule, 200, [], ['groups' => 'vehicule:read']);
    }

    #[Route('/api/vehicule/{id}/edit', name: 'api_vehicule_update', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Vehicule $vehicule, EntityManagerInterface $em, SluggerInterface $slugger): JsonResponse
    {
        $data = $request->request->all();

        $vehicule->setMarque($data['marque']);
        $vehicule->setModele($data['modele']);
        $vehicule->setNbPorte($data['nbPorte']);
        $vehicule->setCouleur($data['couleur']);
        $vehicule->setKilometrage($data['kilometrage']);
        $vehicule->setBoiteDeVitesse($data['boiteDeVitesse'] ?? '');
        $vehicule->setTypeCarburant($data['typeCarburant'] ?? '');
        $vehicule->setPrixJour($data['prixJour'] ?? 0);
        $vehicule->setPresentationVehicule($data['presentationVehicule'] ?? null);
        $vehicule->setRemarque($data['remarque'] ?? null);
        
        if ($data['isUtilitaire'] && $data['isUtilitaire'] == 1) {
            $vehicule->setTypeVehicule('Utilitaire');
            $vehicule->setIsUtilitaire(1);
        } else {
            $vehicule->setTypeVehicule($data['typeVehicule'] ?? '');
            $vehicule->setIsUtilitaire(0);
        }

        $agence = $em->getRepository(Agence::class)->find($data['agence']);
        if (!$agence) {
            return $this->json(['message' => 'Agence non trouvée'], 404);
        }
        $vehicule->setAgence($agence);

        // Si une nouvelle photo de présentation est envoyée
        $photoPresentation = $request->files->get('photoPresentation');
        if ($photoPresentation) {
            $newFilename = FileHelper::getHashedFileName($photoPresentation);
            $photoPresentation->move($this->getParameter('uploads_directory') . '/vehicules', $newFilename);
            $vehicule->setPhotoPresentation('/uploads/vehicules/' . $newFilename);
        }

        // Si de nouvelles photos de véhicules sont envoyées
        $photoVehiculeFiles = $request->files->all('photoVehicule');
        if ($photoVehiculeFiles) {
            $photoVehiculesPaths = [];
            foreach ($photoVehiculeFiles as $photoFile) {
                $newFilename = FileHelper::getHashedFileName($photoFile);
                $photoFile->move($this->getParameter('uploads_directory') . '/vehicules', $newFilename);
                $photoVehiculesPaths[] = '/uploads/vehicules/' . $newFilename;
            }
            // Tu peux aussi décider de fusionner ou d’écraser ici
            $vehicule->setPhotoVehicule($photoVehiculesPaths);
        }

        $em->flush();

        return $this->json($vehicule, 200, [], ['groups' => 'vehicule:read']);
    }

    #[Route('/api/vehicule/{id}', name: 'api_vehicule_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Vehicule $vehicule, ReservationRepository $reservationRepository, EntityManagerInterface $em): JsonResponse
    {
        $today = new \DateTime();
        $futureReservations = $reservationRepository->createQueryBuilder('r')
            ->where('r.vehicule = :vehicule')
            ->andWhere('r.date_fin >= :today')
            ->setParameter('vehicule', $vehicule)
            ->setParameter('today', $today)
            ->getQuery()
            ->getResult();

        if (count($futureReservations) > 0) {
            return $this->json(['error' => 'Ce véhicule a des réservations en cours ou à venir.'], 400);
        }

        $em->remove($vehicule);
        $em->flush();

        return $this->json(['success' => 'Véhicule supprimé.']);
    }

    #[Route('/api/vehicules/search', name: 'api_vehicule_search', methods: ['GET'])]
    #[AllowAnonymous]
    public function search(Request $request, VehiculeRepository $repo): JsonResponse
    {
        $data = $request->query->all();

        $startDate = new \DateTime($data['startDate']);
        $endDate = new \DateTime($data['endDate']);
        $ville = $data['ville'];
        $type = $data['type']; // 'utilitaire' ou 'classique'

        $vehicules = $repo->searchAvailableVehicles($startDate, $endDate, $ville, $type);

        return $this->json($vehicules, 200, [], ['groups' => 'vehicule:read']);
    }
}
