<?php

namespace App\Controller;

use App\Entity\StockHistoric;
use App\Form\StockHistoricType;
use App\Repository\StockHistoricRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/stock/historic')]
class StockHistoricController extends AbstractController
{
    #[Route('/', name: 'app_stock_historic_index', methods: ['GET'])]
    public function index(StockHistoricRepository $stockHistoricRepository): Response
    {
        return $this->render('stock_historic/index.html.twig', [
            'stock_historics' => $stockHistoricRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_stock_historic_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StockHistoricRepository $stockHistoricRepository): Response
    {
        $stockHistoric = new StockHistoric();
        $form = $this->createForm(StockHistoricType::class, $stockHistoric);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stockHistoricRepository->add($stockHistoric);
            return $this->redirectToRoute('app_stock_historic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stock_historic/new.html.twig', [
            'stock_historic' => $stockHistoric,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stock_historic_show', methods: ['GET'])]
    public function show(StockHistoric $stockHistoric): Response
    {
        return $this->render('stock_historic/show.html.twig', [
            'stock_historic' => $stockHistoric,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stock_historic_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, StockHistoric $stockHistoric, StockHistoricRepository $stockHistoricRepository): Response
    {
        $form = $this->createForm(StockHistoricType::class, $stockHistoric);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stockHistoricRepository->add($stockHistoric);
            return $this->redirectToRoute('app_stock_historic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stock_historic/edit.html.twig', [
            'stock_historic' => $stockHistoric,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stock_historic_delete', methods: ['POST'])]
    public function delete(Request $request, StockHistoric $stockHistoric, StockHistoricRepository $stockHistoricRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stockHistoric->getId(), $request->request->get('_token'))) {
            $stockHistoricRepository->remove($stockHistoric);
        }

        return $this->redirectToRoute('app_stock_historic_index', [], Response::HTTP_SEE_OTHER);
    }
}
