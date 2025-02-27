<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: "La date de commande est obligatoire.")]
    #[Assert\Type("\DateTimeInterface", message: "La date de commande doit être une date valide.")]
    private ?\DateTimeInterface $date_commande = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le statut de la commande est obligatoire.")]
    #[Assert\Choice(choices: ["En attente", "Expédiée", "Livrée", "Annulée"], message: "Le statut choisi n'est pas valide.")]
    private ?string $statut = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Le total de la commande est obligatoire.")]
    #[Assert\Positive(message: "Le total doit être un montant positif.")]
    private ?float $total = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[Assert\NotNull(message: "Un client doit être associé à la commande.")]
    private ?User $id_client = null;

    #[ORM\ManyToMany(targetEntity: Produit::class, inversedBy: "commandes")]
    #[ORM\JoinTable(name: "produit_commande")]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->date_commande;
    }

    public function setDateCommande(\DateTimeInterface $date_commande): static
    {
        $this->date_commande = $date_commande;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;
        return $this;
    }

    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->addCommande($this);
        }
        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            $produit->removeCommande($this);
        }
        return $this;
    }
}
