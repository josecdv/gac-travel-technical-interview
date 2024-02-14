<?php
namespace App\Controller;

use App\Entity\Users;
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
        $entityType = Users::class; 
        $this->fixtures->setEntityType('Users');
        $this->fixtures->load($this->entityManager, $entityType);
        // $entityType = Users::class; 
        // $this->fixtures->load($this->entityManager, $entityType); // Ejecutar las fixtures para cargar los datos

         return $this->redirectToRoute('users_index'); // Redireccionar a la página de productos (o a donde sea necesario)
    }

    #[Route('/reload-data-categorias', name: 'reload_data_categorias', methods: ['GET'])]
    public function reloadDataC(): Response
    {
        $entityType = Users::class; 
        $this->fixtures->setEntityType('Categories');
        $this->fixtures->load($this->entityManager, $entityType);
        // $entityType = Users::class; 
        // $this->fixtures->load($this->entityManager, $entityType); // Ejecutar las fixtures para cargar los datos

         return $this->redirectToRoute('categories_index'); // Redireccionar a la página de productos (o a donde sea necesario)
    }

    #[Route('/reload-data-prod', name: 'reload_data_prod', methods: ['GET'])]
    public function reloadDataP(): Response
    {
        $entityType = Users::class; 
        $this->fixtures->setEntityType('Products');
        $this->fixtures->load($this->entityManager, $entityType);
        // $entityType = Users::class; 
        // $this->fixtures->load($this->entityManager, $entityType); // Ejecutar las fixtures para cargar los datos

         return $this->redirectToRoute('products_index'); // Redireccionar a la página de productos (o a donde sea necesario)
    }
}