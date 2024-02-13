<?php

namespace App\Controller;

use App\Entity\StockHistoric;
use App\Form\StockHistoricType;
use App\Repository\StockHistoricRepository;
use PhpParser\Node\Expr\Empty_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/stock/historic')]
class StockHistoricController extends AbstractController
{
    #[Route('/', name: 'stock_historic_index', methods: ['GET'])]
    public function index(StockHistoricRepository $stockHistoricRepository): Response
    {
        return $this->render('stock_historic/index.html.twig', [
            'stock_historics' => $stockHistoricRepository->findAll(),
        ]); 
    }


    #[Route('/{id}', name: 'stock_historic_index_id', methods: ['GET'])]
    public function index_id(StockHistoricRepository $stockHistoricRepository, Request $request, int $id): Response
    {
        //$id = $request->query->get('id');
        if ($id) {
            $stockHistorics = $stockHistoricRepository->findByProductId($id);
        } else {
             $stockHistorics = null;//$stockHistoricRepository->findAll();
         }

        return $this->render('stock_historic/index.html.twig', [
            'stock_historics' => $stockHistorics,
        ]);
    }

    #[Route('/{id}/user', name: 'stock_historic_index_user_id', methods: ['GET'])]
    public function index_user_id(StockHistoricRepository $stockHistoricRepository, Request $request, int $id): Response
    {
        //$id = $request->query->get('id');
        if ($id) {
            $stockHistorics = $stockHistoricRepository->findByUserId($id);
        } else {
             $stockHistorics = null;//$stockHistoricRepository->findAll();
         }

        return $this->render('stock_historic/index.html.twig', [
            'stock_historics' => $stockHistorics,
        ]);
    }

}
