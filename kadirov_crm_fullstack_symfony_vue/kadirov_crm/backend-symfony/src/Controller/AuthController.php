<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/auth')]
class AuthController extends AbstractController
{
    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $email = trim($data['email'] ?? '');
        $password = (string) ($data['password'] ?? '');

        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user || ($user->getPlainPassword() !== $password && !$passwordHasher->isPasswordValid($user, $password))) {
            return $this->json(['error' => 'Email yoki parol noto'g'ri!'], 401);
        }

        $user->setLastActive('Hozir onlayn');
        $em->flush();

        return $this->json([
            'message' => 'Tizimga muvaffaqiyatli kirildi',
            'token' => base64_encode($user->getEmail() . ':' . time()),
            'user' => $user->toArray(),
        ]);
    }
}
