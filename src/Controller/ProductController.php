<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ProductService;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'product_index')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAllPriceDec();
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

#[Route('/product/new', name: 'product_new')]
#[Route('/product/edit/{id}', name: 'product_edit')]
public function form(Request $request, ProductRepository $productRepository, EntityManagerInterface $manager, $id = null): Response {
    $product = $id ? $productRepository->find($id) : new Product();

    $form = $this->createForm(ProductType::class, $product);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $manager->persist($product);
        $manager->flush();

        $this->addFlash('success', $id ? 'Produit modifié avec succès.' : 'Produit créé avec succès.');
        return $this->redirectToRoute('product_index');
    }

    return $this->render('product/new.html.twig', [
        'form' => $form->createView(),
        'titre' => $id ? 'Modification' : 'Création',
    ]);
}

#[Route('/product/delete/{id}', name: 'product_delete')]
    public function delete(Product $product, EntityManagerInterface $manager): RedirectResponse
    {
        if ($product) {
            $manager->remove($product);
            $manager->flush();

            $this->addFlash('success', 'Produit supprimé avec succès.');

        } else {
            $this->addFlash('error', 'Le produit demandé n\'existe pas.');
        }

        return $this->redirectToRoute('product_index');
    }


    #[Route('/product/export.csv', name: 'product_export')]
    public function export(ProductRepository $productRepository): Response
    {
        return ProductService::csvExport($productRepository);
    }
}
