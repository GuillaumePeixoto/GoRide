<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Entity\Agence;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/api/vehicules')]
class VehiculeController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(VehiculeRepository $vehiculeRepository): JsonResponse
    {
        $vehicules = $vehiculeRepository->findAll();
        return $this->json($vehicules, 200, [], ['groups' => 'vehicule:read']);
    }

    #[Route('', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): JsonResponse
    {
        // $data = json_decode($request->getContent(), true);
        $data = $request->request->all();

        $vehicule = new Vehicule();
        $vehicule->setMarque($data['marque']);
        $vehicule->setModele($data['modele']);
        $vehicule->setNbPorte($data['nb_porte']);
        $vehicule->setCouleur($data['couleur']);
        $vehicule->setPhotoPresentation(null);
        $vehicule->setKilometrage($data['kilometrage']);
        $vehicule->setPresentationVehicule($data['presentation_vehicule'] ?? null);
        $vehicule->setRemarque($data['remarque'] ?? null);
        $vehicule->setPhotoVehicule([]);
        if($data['isUtilitaire']){
            $vehicule->setTypeVehicule('utilitaire');
            $vehicule->setIsUtilitaire(1);
        } else {
            $vehicule->setTypeVehicule($data['type_vehicule'] ?? '');
            $vehicule->setIsUtilitaire(0);
        }
        
        $agence = $em->getRepository(Agence::class)->find($data['agence']);
        if (!$agence) {
            return $this->json(['message' => 'Agence non trouvée'], 404);
        }
        
        $vehicule->setAgence($agence);

        $photoPresentation = $request->files->get('photo_presentation');
        if ($photoPresentation) {
            $originalFilename = pathinfo($photoPresentation->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoPresentation->guessExtension();

            $photoPresentation->move($this->getParameter('uploads_directory') . '/vehicules', $newFilename);
            $vehicule->setPhotoPresentation('/uploads/vehicules/' . $newFilename);
        }

        // Gestion des photos multiples
        $photoVehiculeFiles = $request->files->all('photo_vehicule');
        $photoVehiculesPaths = [];
        if ($photoVehiculeFiles) {
            foreach ($photoVehiculeFiles as $photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                $photoFile->move($this->getParameter('uploads_directory') . '/vehicules', $newFilename);
                $photoVehiculesPaths[] = '/uploads/vehicules/' . $newFilename;
            }
            $vehicule->setPhotoVehicule($photoVehiculesPaths);
        }


        $em->persist($vehicule);
        $em->flush();

        return $this->json($vehicule, 201, [], ['groups' => 'vehicule:read']);
    }
}
