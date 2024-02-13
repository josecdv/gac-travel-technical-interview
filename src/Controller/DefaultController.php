<?php
namespace App\Controller;

use App\DataFixtures\AppFixtures;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class DefaultController extends AbstractController
{
    private $fixtures;
    private $entityManager;

    public function __construct(AppFixtures $fixtures, EntityManagerInterface $entityManager)
    {
        $this->fixtures = $fixtures;
        $this->entityManager = $entityManager;
    }

    #[Route('/reload-data', name: 'reload_data', methods: ['GET'])]
    public function reloadData(): Response
    {
        $this->fixtures->load($this->entityManager); // Ejecutar las fixtures para cargar los datos

        return $this->redirectToRoute('users_index'); // Redireccionar a la página de productos (o a donde sea necesario)
    }
}