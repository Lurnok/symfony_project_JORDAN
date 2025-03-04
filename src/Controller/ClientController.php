<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Security\Voter\ClientVoter;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClientController extends AbstractController
{
    #[Route('/client', name: 'client_index')]
    public function index(ClientRepository $clientRepository): Response
    {
        $this->denyAccessUnlessGranted(ClientVoter::VIEW, null);

        $clients = $clientRepository->findAll();

        return $this->render('client/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/client/new', name: 'client_new')]
    #[Route('/client/edit/{id}', name: 'client_edit')]
    public function new(Request $request, ClientRepository $clientRepository, EntityManagerInterface $manager, $id = null): Response
    {

        $client = $id ? $clientRepository->find($id) : new Client();

        if($id){
            $this->denyAccessUnlessGranted(ClientVoter::EDIT, null);
        }
        else{
            $this->denyAccessUnlessGranted(ClientVoter::CREATE, null);
        }

        dump($request->request->all());

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($client->getCreatedAt() === null) {
                $client->setCreatedAt(new DateTimeImmutable());
            }
            

            $manager->persist($client);
            $manager->flush();

            $this->addFlash('success', $id ? 'Client modifié avec succès.' : 'Client créé avec succès.');
            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/new.html.twig', [
            'form' => $form->createView(),
            'titre' => $id ? 'Modification' : 'Création',
        ]);
    }

    #[Route('/client/delete/{id}', name: 'client_delete')]
    public function delete(Client $client, Request $request, EntityManagerInterface $manager): Response
    {
        if ($client) {
            $manager->remove($client);
            $manager->flush();

            $this->addFlash('success', 'Client supprimé avec succès.');

        } else {
            $this->addFlash('error', 'Le client demandé n\'existe pas.');
        }

        return $this->redirectToRoute('client_index');
    }
}
