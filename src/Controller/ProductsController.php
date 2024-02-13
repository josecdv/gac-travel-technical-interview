<?php

namespace App\Controller;


use App\Form\StockType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Categories;
use App\Entity\Products;
use App\Entity\StockHistoric;
use App\Form\ProductsType;
use App\Form\ProductsTypeE;
use App\Repository\ProductsRepository;
use App\Repository\UsersRepository;
use App\Repository\StockHistoricRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'products_index', methods: ['GET'])]
    public function index(ProductsRepository $productsRepository): Response
    {
        return $this->render('products/index.html.twig', [
            'products' => $productsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'products_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        // Load the categories from the database
        $categories = $this->getDoctrine()
        ->getRepository(Categories::class)
        ->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('products/new.html.twig', [
            'product' => $product,
            'form' => $form,
            'categories' => $categories, // Pass the categories variable to the template
        ]);
    }

    #[Route('/{id}', name: 'products_show', methods: ['GET'])]
    public function show(Products $product): Response
    {
        return $this->render('products/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'products_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Products $product): Response
    {
        $form = $this->createForm(ProductsTypeE::class, $product);
        $form->handleRequest($request);

        
        // Load the categories from the database
        $categories = $this->getDoctrine()
        ->getRepository(Categories::class)
        ->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('products/edit.html.twig', [
            'product' => $product,
            'form' => $form,
            'categories' => $categories, // Pass the categories variable to the template
        ]);
    }

    #[Route('/{id}', name: 'products_delete', methods: ['POST'])]
    public function delete(Request $request, Products $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('products_index', [], Response::HTTP_SEE_OTHER);
    }

     
     #[Route("/products/{id}/stock", name: 'products_stock')]
     
    public function stock(Request $request, Products $product,StockHistoricRepository $stockHistoricRepository, UsersRepository $userRepository): Response
    {
        $form = $this->createForm(StockType::class, null, [
            'product' => $product,
        ]);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $stock = $form->get('stock')->getData();
            $stockActual = $product->getStock();
            $nuevoStock = $stockActual + $stock;
    
            if ($nuevoStock < 0) {
                $this->addFlash('error', 'No se puede eliminar más stock del existente.');
            } else {
                $product->setStock($nuevoStock);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                // // Get the current user
                // $user = $userRepository->findOneBy(['id' => $this->getUser()->getId()]);
                
                // // Create a new StockHistoric record
                // $stockHistoric = new StockHistoric();
                // $stockHistoric->setUserId($user);
                // $stockHistoric->setProductsId($product);
                // $stockHistoric->setStock($stock);
                // $stockHistoric->setCreatedAt(new \DateTime());

                // // Save the StockHistoric record
                // $entityManager->persist($stockHistoric);
                // $entityManager->flush();
                // $this->addFlash('success', 'El stock del producto se ha actualizado correctamente.');
            }
    
           return $this->redirectToRoute('products_index');
        }
    
        return $this->renderForm('products/stock.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }
}
