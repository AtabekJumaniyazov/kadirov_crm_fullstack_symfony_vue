<?php

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/companies')]
class CompanyController extends AbstractController
{
    #[Route('', name: 'api_companies_index', methods: ['GET'])]
    public function index(Request $request, CompanyRepository $companyRepository): JsonResponse
    {
        $companies = $companyRepository->searchCompanies(
            $request->query->get('name', ''),
            $request->query->get('email', ''),
            $request->query->get('password', ''),
            $request->query->get('lastActive', '')
        );

        return $this->json(array_map(fn(Company $c) => $c->toArray(), $companies));
    }

    #[Route('', name: 'api_companies_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $company = new Company();
        $company->setName($data['name'] ?? '');
        $company->setEmail($data['email'] ?? '');
        $company->setPassword($data['password'] ?? '12345678');
        $company->setLastActive('Hozirgina');

        $em->persist($company);
        $em->flush();

        return $this->json([
            'message' => 'Kompaniya muvaffaqiyatli qo'shildi',
            'company' => $company->toArray(),
        ], 201);
    }

    #[Route('/{id}', name: 'api_companies_update', methods: ['PUT'])]
    public function update(int $id, Request $request, CompanyRepository $companyRepository, EntityManagerInterface $em): JsonResponse
    {
        $company = $companyRepository->find($id);
        if (!$company) {
            return $this->json(['error' => 'Kompaniya topilmadi'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['name'])) $company->setName($data['name']);
        if (isset($data['email'])) $company->setEmail($data['email']);
        if (isset($data['password'])) $company->setPassword($data['password']);
        $company->setLastActive('Hozirgina');

        $em->flush();

        return $this->json([
            'message' => 'Ma'lumotlat muaffaqiyatli o'zgartirildi',
            'company' => $company->toArray(),
        ]);
    }

    #[Route('/{id}', name: 'api_companies_delete', methods: ['DELETE'])]
    public function delete(int $id, CompanyRepository $companyRepository, EntityManagerInterface $em): JsonResponse
    {
        $company = $companyRepository->find($id);
        if (!$company) {
            return $this->json(['error' => 'Kompaniya topilmadi'], 404);
        }

        $em->remove($company);
        $em->flush();

        return $this->json(['message' => 'Kompaniya o'chirildi']);
    }
}
