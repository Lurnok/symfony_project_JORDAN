<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ClientService;

#[AsCommand(
    name: 'app:add-client',
    description: 'Add a short description for your command',
)]
class AddClientCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private ClientService $clientService;

    public function __construct(EntityManagerInterface $entityManager, ClientService $clientService)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->clientService = $clientService;
    }
    protected function configure(): void
    {
        $this
            ->addArgument('firstname', InputArgument::REQUIRED, 'Le prenom du client')
            ->addArgument('lastname', InputArgument::REQUIRED, 'Le nom du client')
            ->addArgument('email', InputArgument::REQUIRED, 'Le mail du client')
            ->addArgument('phoneNumber', InputArgument::REQUIRED, 'Le numéro de téléphone du client')
            ->addArgument('address', InputArgument::REQUIRED, 'L\'adresse du client')
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        do {
            $firstname = $io->ask('Quel est le prénom du client ?', null, function ($answer) {
                if (!$this->clientService->isValidName($answer)) {
                    throw new \RuntimeException("Le prénom ne doit pas contenir de caractères spéciaux ni être vide.");
                }
                return $answer;
            });
        } while (!$this->clientService->isValidName($firstname));
        $input->setArgument('firstname', $firstname);

        do {
            $lastname = $io->ask('Quel est le nom du client ?', null, function ($answer) {
                if (!$this->clientService->isValidName($answer)) {
                    throw new \RuntimeException("Le nom ne doit pas contenir de caractères spéciaux ni être vide.");
                }
                return $answer;
            });
        } while (!$this->clientService->isValidName($lastname));
        $input->setArgument('lastname', $lastname);

        do {
            $email = $io->ask('Quel est l\'email du client ?', null, function ($answer) {
                if (!$this->clientService->isValidEmail($answer)) {
                    throw new \RuntimeException("Email invalide.");
                }
                return $answer;
            });
        } while (!$this->clientService->isValidEmail($email));

        do {
            if (!$this->clientService->isEmailAvailable($email)) {
                throw new \RuntimeException("Email déjà utilisé.");
            }
            $input->setArgument('email', $email);
        } while (!$this->clientService->isEmailAvailable($email));

        $phoneNumber = $io->ask('Quel est le numéro de téléphone du client ?');
        $input->setArgument('phoneNumber', $phoneNumber);

        $address = $io->ask('Quelle est l\'adresse du client ?');
        $input->setArgument('address', $address);
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $firstname = $input->getArgument('firstname');
        $lastname = $input->getArgument('lastname');
        $email = $input->getArgument('email');
        $phoneNumber = $input->getArgument('phoneNumber');
        $address = $input->getArgument('address');

        $client = new Client();
        $client->setFirstname($firstname);
        $client->setLastname($lastname);
        $client->setEmail($email);
        $client->setPhoneNumber($phoneNumber);
        $client->setAddress($address);
        $client->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($client);

        $this->entityManager->flush();

        $io->success("Le client {$firstname} {$lastname} a été ajouté avec succès !");

        return Command::SUCCESS;
    }
}
