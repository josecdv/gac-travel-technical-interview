<?php
namespace App\Controller;

use App\Form\Type\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Products;
use App\Entity\Categories;
use Symfony\Component\HttpFoundation\Request;


#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->processForm(new Products(), $request, $entityManager);
    }

    #[Route('/list', name: 'list-products')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager->getRepository(Products::class)->findAll();

        return $this->render('products.html.twig', [
            'products' => $products
        ]);
    }

    // TODO:
    /*#[Route('/delete/{id}')]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
    }*/

    #[Route('/edit/{id}')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Products::class)->find($id);

        return $this->processForm($product, $request, $entityManager);
    }

    private function processForm(Products $product, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $categoryId = $request->request->get('category');
            $product->setCategory($entityManager->getRepository(Categories::class)->find($categoryId));
            $product->setCreatedAt(new \DateTime());
            $product->setStock(0);

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('list-products');
        }
        
        return $this->renderForm('add-product.html.twig', [
            'form' => $form,
            'product' => $product,
            'categories' => $entityManager->getRepository(Categories::class)->findAll()
        ]);
    }

    #[Route('/stock/{id}', methods: ['GET'])]
    public function stockForm(int $id, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Products::class)->find($id);

        return $this->renderForm('stock.html.twig', [
            'product' => $product,
            'enoughStock' => true
        ]);
    }

    #[Route('/stock/{id}', methods: ['POST'])]
    public function stockUpdate(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $stockChange = $request->request->get('stock');
        $product = $entityManager->getRepository(Products::class)->find($id);
        $newStock = $product->getStock() + $stockChange;

        if ($newStock < 0) {
            return $this->renderForm('stock.html.twig', [
                'product' => $product,
                'enoughStock' => false
            ]);
        }

        $product->setStock($newStock);

        $entityManager->persist($product);
        $entityManager->flush();        

        return $this->redirectToRoute('list-products');
    }
}