<?php

namespace App\Service;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Response;

class ClientService
{

    private ClientRepository $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function isValidEmail(string $email): bool
    {
        return preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email) === 1;
    }

    public function isValidName(string $name): bool
    {
        return preg_match('/^[a-zA-ZÀ-ÖØ-öø-ÿ-]+(?: [a-zA-ZÀ-ÖØ-öø-ÿ-]+)*$/', $name) === 1;
    }

    public function isEmailAvailable(string $email): bool
    {
        $email = trim($email);

        $query = $this->clientRepository->createQueryBuilder('c')
            ->where('LOWER(c.email) = LOWER(:email)')
            ->setParameter('email', $email)
            ->getQuery();


        $result = $query->getOneOrNullResult();

        return $result === null;
    }
}
