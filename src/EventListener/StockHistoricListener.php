<?php

namespace App\EventListener;


use App\Entity\Products;
use App\Entity\StockHistoric;
use App\Repository\ProductsRepository;
use App\Repository\UsersRepository;
use App\Repository\StockHistoricRepository;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Security;
use Doctrine\DBAL\Connection;

class StockHistoricListener
{
    private $entityManager;
    private $productsRepository;
    private $stockHistoricRepository;
    private $security;
    private $connection;

    public function __construct(EntityManagerInterface $entityManager, ProductsRepository $productsRepository, StockHistoricRepository $stockHistoricRepository, Security $security, Connection $connection)
    {
        $this->entityManager = $entityManager;
        $this->productsRepository = $productsRepository;
        $this->stockHistoricRepository = $stockHistoricRepository;
        $this->security = $security;
        $this->connection = $connection;
    }

    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if (!$entity instanceof Products) {
            return;
        }

        $unitOfWork = $this->entityManager->getUnitOfWork();
        $changeset = $unitOfWork->getEntityChangeSet($entity);
        
        if (!isset($changeset['stock'])) {
            return;
        }

        // Get the current user
        $user = $this->getUser();

        // Create a new StockHistoric record
        $stockHistoric = new StockHistoric();
        $stockHistoric->setUserId($user);
        $stockHistoric->setProductsId($entity);
        $stockHistoric->setStock($changeset['stock'][1]-$changeset['stock'][0]);
        $stockHistoric->setCreatedAt(new \DateTime());

        // Save the StockHistoric record
        $this->entityManager->persist($stockHistoric);
        $this->entityManager->flush();

    }

    private function getUser()
    {
        $token = $this->security->getToken();
        if ($token && $token->getUser()) {
            return $token->getUser();
        }

        // En este ejemplo, simplemente devolvemos un identificador de usuario fijo (1)
        return 1;
    }
}