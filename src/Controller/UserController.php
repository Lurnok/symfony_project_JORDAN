<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;
    }

    #[Route('/user', name: 'user_index')]
    public function index(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::VIEW,null);

        $users = $userRepository->findAll();


        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users
        ]);
    }

    #[Route('/user/new', name: 'user_new')]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::CREATE,null);

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $userPasswordHasherInterface->hashPassword($user,$user->getPassword());
            $user->setPassword($hashedPassword);


            $this->manager->persist($user);
            $this->manager->flush();

            $this->addFlash('success', 'Utilisateur créé avec succès!');

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
            'titre' => "Création"
        ]);
    }

    #[Route('/user/delete/{id}', name: 'user_delete')]
    public function delete(User $user, UserRepository $userRepository): RedirectResponse
    {
        $this->denyAccessUnlessGranted(UserVoter::DELETE,null);
        if ($user) {
            $this->manager->remove($user);
            $this->manager->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès.');

        } else {
            $this->addFlash('error', 'L\'utilisateur demandé n\'existe pas.');
        }

        return $this->redirectToRoute('user_index');
    }

    #[Route('/user/edit/{id}', name: 'user_edit')]
    public function edit(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface, UserRepository $userRepository, $id): Response
    {
        $user = $userRepository->find($id);

        $this->denyAccessUnlessGranted(UserVoter::EDIT, $user);

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->manager->persist($user);
            $this->manager->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès!');

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
            'titre' => "Modification"
        ]);
    }
}
