<?php

namespace App\Entity;

use App\Repository\StockHistoricRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockHistoricRepository::class)
 */
class StockHistoric
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="float")
     */
    private $stock;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="stockHistorics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Users;

    /**
     * @ORM\ManyToOne(targetEntity=Products::class, inversedBy="stockHistorics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Products;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getStock(): ?float
    {
        return $this->stock;
    }

    public function setStock(float $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->Users;
    }

    public function setUsers(?Users $Users): self
    {
        $this->Users = $Users;

        return $this;
    }

    public function getProducts(): ?Products
    {
        return $this->Products;
    }

    public function setProducts(?Products $Products): self
    {
        $this->Products = $Products;

        return $this;
    }
}
