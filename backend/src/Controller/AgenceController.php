<?php

    namespace App\Controller;

    use App\Entity\Agence;
    use App\Repository\AgenceRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;
    use Symfony\Component\HttpFoundation\Response;

    #[Route('/api/agences', name: 'api_agences_')]
    class AgenceController extends AbstractController
    {
        #[Route('', name: 'list', methods: ['GET'])]
        public function list(AgenceRepository $agenceRepository): JsonResponse
        {
            $agences = $agenceRepository->findAll();
            return new JsonResponse([$agences]);
        }

        #[Route('', name: 'create', methods: ['POST'])]
        #[IsGranted('ROLE_ADMIN')]
        public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
        {
            $data = json_decode($request->getContent(), true);

            if (!$data || !isset($data['nom'], $data['adresse'], $data['ville'], $data['telephone'])) {
                return new JsonResponse(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
            }

            $agence = new Agence();
            $agence->setNom($data['nom']);
            $agence->setAdresse($data['adresse']);
            $agence->setVille($data['ville']);
            $agence->setTelephone($data['telephone']);

            $entityManager->persist($agence);
            $entityManager->flush();

            return new JsonResponse(['message' => 'Agence ajoutée avec succès'], Response::HTTP_CREATED);
        }
    }
