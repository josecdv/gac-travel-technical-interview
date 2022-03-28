<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductsType;
use App\Repository\ProductsRepository;
use Doctrine\DBAL\Query;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/stock/products')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'app_products_index', methods: ['GET'])]
    public function index(ProductsRepository $productsRepository): Response
    {
        return $this->render('products/index.html.twig', [
            'products' => $productsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_products_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductsRepository $productsRepository): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setStock(0);
            $product->setCreatedAt(new \DateTime());
            $productsRepository->add($product);

            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('products/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /* #[Route('/{id}', name: 'app_products_show', methods: ['GET'])]
    public function show(Products $product): Response
    {
        return $this->render('products/show.html.twig', [
            'product' => $product,
        ]);
    } */

    #[Route('/{id}/edit', name: 'app_products_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Products $product, ProductsRepository $productsRepository): Response
    {
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productsRepository->add($product);
            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('products/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/increaseStock', name: 'increase_stock', methods: ['GET', 'POST'])]
    public function increaseStock(Request $request, Products $product, ProductsRepository $productsRepository): Response
    {

        $form = $this->createFormBuilder()
            ->add('amountproduct', NumberType::class)
            ->add('save', SubmitType::class)
            ->getForm();
        $error = [];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $increase = $data['amountproduct'];
            $stock = $product->getStock();
            if ($increase + $stock > 0) {
                $em = $this->getDoctrine()->getManager();
                $product->setStock($stock + $increase);
                $em->persist($product);
                $em->flush();
                return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $error ['stock']='stock insuficiente';
            }
        }

        return $this->renderForm('products/increaseStock.html.twig', [
            'product' => $product,
            'form' => $form,
            'error' => $error,
        ]);
    }
}
