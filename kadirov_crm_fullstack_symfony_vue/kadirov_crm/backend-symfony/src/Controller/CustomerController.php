<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CompanyRepository;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/customers')]
class CustomerController extends AbstractController
{
    #[Route('', name: 'api_customers_index', methods: ['GET'])]
    public function index(Request $request, CustomerRepository $customerRepository): JsonResponse
    {
        $companyId = $request->query->get('companyId');
        $customers = $customerRepository->searchCustomers(
            $request->query->get('name', ''),
            $request->query->get('email', ''),
            $request->query->get('password', ''),
            $request->query->get('workplace', ''),
            $request->query->get('lastActive', ''),
            $companyId ? (int) $companyId : null
        );

        return $this->json(array_map(fn(Customer $c) => $c->toArray(), $customers));
    }

    #[Route('', name: 'api_customers_create', methods: ['POST'])]
    public function create(
        Request $request,
        CompanyRepository $companyRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $company = $companyRepository->find((int) ($data['companyId'] ?? 0));

        if (!$company) {
            return $this->json(['error' => 'Ishlash joyi (kompaniya) tanlanishi shart!'], 400);
        }

        $customer = new Customer();
        $customer->setName($data['name'] ?? '');
        $customer->setEmail($data['email'] ?? '');
        $customer->setPassword($data['password'] ?? '12345678');
        $customer->setCompany($company);
        $customer->setLastActive('Hozirgina');

        $em->persist($customer);
        $em->flush();

        return $this->json([
            'message' => 'Mijoz muvaffaqiyatli qo'shildi',
            'customer' => $customer->toArray(),
        ], 201);
    }

    #[Route('/{id}', name: 'api_customers_update', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        CustomerRepository $customerRepository,
        CompanyRepository $companyRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        $customer = $customerRepository->find($id);
        if (!$customer) {
            return $this->json(['error' => 'Mijoz topilmadi'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['name'])) $customer->setName($data['name']);
        if (isset($data['email'])) $customer->setEmail($data['email']);
        if (isset($data['password'])) $customer->setPassword($data['password']);
        if (!empty($data['companyId'])) {
            $company = $companyRepository->find((int) $data['companyId']);
            if ($company) {
                $customer->setCompany($company);
            }
        }
        $customer->setLastActive('Hozirgina');

        $em->flush();

        return $this->json([
            'message' => 'Ma'lumotlat muaffaqiyatli o'zgartirildi',
            'customer' => $customer->toArray(),
        ]);
    }

    #[Route('/{id}', name: 'api_customers_delete', methods: ['DELETE'])]
    public function delete(int $id, CustomerRepository $customerRepository, EntityManagerInterface $em): JsonResponse
    {
        $customer = $customerRepository->find($id);
        if (!$customer) {
            return $this->json(['error' => 'Mijoz topilmadi'], 404);
        }

        $em->remove($customer);
        $em->flush();

        return $this->json(['message' => 'Mijoz o'chirildi']);
    }
}
