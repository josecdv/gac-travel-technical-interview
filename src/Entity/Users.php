<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 */
class Users
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=StockHistoric::class, mappedBy="user")
     */
    private $stockHistorics;

    public function __construct()
    {
        $this->stockHistorics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

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
            $stockHistoric->setUser($this);
        }

        return $this;
    }

    public function removeStockHistoric(StockHistoric $stockHistoric): self
    {
        if ($this->stockHistorics->removeElement($stockHistoric)) {
            // set the owning side to null (unless already changed)
            if ($stockHistoric->getUser() === $this) {
                $stockHistoric->setUser(null);
            }
        }

        return $this;
    }
}
