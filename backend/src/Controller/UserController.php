<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Repository\UserRepository;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    #[Route('/api/users', methods: ['GET'])]
    public function list(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        return $this->json($users, 200, [], ['groups' => 'user:read']);
    }

    #[Route('/user/inscription', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        // Vérification des champs obligatoires
        $requiredFields = ['email', 'password', 'confirmPassword', 'nom', 'prenom', 'adresse', 'ville', 'codePostal', 'birthdate'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return new JsonResponse(['error' => "Le champ '$field' est requis."], 400);
            }
        }
    
        // Vérifier si l'email est déjà utilisé
        $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return new JsonResponse(['error' => 'Cet email est déjà utilisé.'], 400);
        }
    
        // Vérifier que les mots de passe correspondent
        if ($data['password'] !== $data['confirmPassword']) {
            return new JsonResponse(['error' => 'Les mots de passe ne correspondent pas.'], 400);
        }
    
        // (Optionnel) Validation basique de l'email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['error' => 'Email invalide.'], 400);
        }
    
        // (Optionnel) Validation mot de passe (longueur mini, etc.)
        if (strlen($data['password']) < 8) {
            return new JsonResponse(['error' => 'Le mot de passe doit contenir au moins 8 caractères.'], 400);
        }
    
        // Création de l'utilisateur
        $user = new User();
        $user->setEmail($data['email']);
        $user->setNom($data['nom']);
        $user->setPrenom($data['prenom']);
        $user->setAdresse($data['adresse']);
        $user->setVille($data['ville']);
        $user->setCodePostal($data['codePostal']);
        
        try {
            $user->setDateNaissance(new \DateTime($data['birthdate']));
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Date de naissance invalide.'], 400);
        }
    
        $user->setRoles(['ROLE_USER']);
    
        $hashedPassword = $hasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);
    
        $em->persist($user);
        $em->flush();
    
        return new JsonResponse(['message' => 'Inscription réussie'], 201);
    }    

}
