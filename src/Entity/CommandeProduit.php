<?php

namespace App\Entity;

use App\Repository\CommandeProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeProduitRepository::class)]
class CommandeProduit
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: "commandeProduits")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commande $commande = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: "commandeProduits")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\Column(type: "integer")]
    #[Assert\NotBlank(message: "La quantité est obligatoire.")]
    #[Assert\Positive(message: "La quantité doit être supérieure à zéro.")]
    private ?int $quantite = null;

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;
        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;
        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }
}
