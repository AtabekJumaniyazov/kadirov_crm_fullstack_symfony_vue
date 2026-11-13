<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/users')]
class UserController extends AbstractController
{
    #[Route('', name: 'api_users_index', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->searchUsers(
            $request->query->get('name', ''),
            $request->query->get('email', ''),
            $request->query->get('password', ''),
            $request->query->get('lastActive', '')
        );

        return $this->json(array_map(fn(User $u) => $u->toArray(), $users));
    }

    #[Route('', name: 'api_users_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setName($data['name'] ?? '');
        $user->setEmail($data['email'] ?? '');
        $plainPassword = $data['password'] ?? '12345678';
        $user->setPlainPassword($plainPassword);
        $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
        $user->setLastActive('Hozirgina');

        $em->persist($user);
        $em->flush();

        return $this->json([
            'message' => 'Foydalanuvchi muvaffaqiyatli qo'shildi',
            'user' => $user->toArray(),
        ], 201);
    }

    #[Route('/{id}', name: 'api_users_update', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $user = $userRepository->find($id);
        if (!$user) {
            return $this->json(['error' => 'Foydalanuvchi topilmadi'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['name'])) $user->setName($data['name']);
        if (isset($data['email'])) $user->setEmail($data['email']);
        if (!empty($data['password'])) {
            $user->setPlainPassword($data['password']);
            $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
        }
        $user->setLastActive('Hozirgina');

        $em->flush();

        return $this->json([
            'message' => 'Ma'lumotlat muaffaqiyatli o'zgartirildi',
            'user' => $user->toArray(),
        ]);
    }

    #[Route('/{id}', name: 'api_users_delete', methods: ['DELETE'])]
    public function delete(int $id, UserRepository $userRepository, EntityManagerInterface $em): JsonResponse
    {
        $user = $userRepository->find($id);
        if (!$user) {
            return $this->json(['error' => 'Foydalanuvchi topilmadi'], 404);
        }

        $em->remove($user);
        $em->flush();

        return $this->json(['message' => 'Foydalanuvchi o'chirildi']);
    }
}
