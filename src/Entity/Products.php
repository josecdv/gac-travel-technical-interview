<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductsRepository::class)
 */
class Products
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;


    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $stock;

    /**
     * @ORM\OneToMany(targetEntity=StockHistoric::class, mappedBy="Products")
     */
    private $stockHistorics;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    public function __construct()
    {
        $this->stockHistorics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function setStock(?float $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Collection<int, StockHistoric>
     */
    public function getStockHistorics(): Collection
    {
        return $this->stockHistorics;
    }

    public function addStockHistoric(StockHistoric $stockHistoric): self
    {
        if (!$this->stockHistorics->contains($stockHistoric)) {
            $this->stockHistorics[] = $stockHistoric;
            $stockHistoric->setProducts($this);
        }

        return $this;
    }

    public function removeStockHistoric(StockHistoric $stockHistoric): self
    {
        if ($this->stockHistorics->removeElement($stockHistoric)) {
            // set the owning side to null (unless already changed)
            if ($stockHistoric->getProducts() === $this) {
                $stockHistoric->setProducts(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Categories
    {
        return $this->category;
    }

    public function setCategory(?Categories $category): self
    {
        $this->category = $category;

        return $this;
    }
}
