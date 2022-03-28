<?php

namespace App\EventListener;

use App\Entity\Products;
use App\Entity\StockHistoric;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class StokChanged
{
    private $token_storage;
    function __construct(TokenStorageInterface $token_storage)
    {
        $this->token_storage = $token_storage;
    }
    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function postUpdate(Products $product, LifecycleEventArgs $event): void
    {
        $user = $this->token_storage->getToken()->getUser();
        //$newLogStock = new StockHistoric();
        //$newLogStock->setCreatedAt();
        //$newLogStock->setStock();
        //$newLogStock->setUsers($user);
        //$newLogStock->setProducts($product);
        
    }
}
