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
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le statut de la commande est obligatoire.")]
    #[Assert\Choice(choices: ["En attente", "Expédiée", "Livrée", "Annulée"], message: "Le statut choisi n'est pas valide.")]
    private ?string $statut = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Le total de la commande est obligatoire.")]
    #[Assert\Positive(message: "Le total doit être un montant positif.")]
    private ?float $total = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(name: "id_client_id", referencedColumnName: "id", nullable: true)]
    #[Assert\NotNull(message: "Un client doit être associé à la commande.")]
    private ?User $client = null;

    #[ORM\ManyToMany(targetEntity: Produit::class, inversedBy: "commandes")]
    //#[ORM\JoinTable(name: "commande_produit")]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): static
    {
        $this->dateCommande = $dateCommande;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        if (!in_array($statut, ["En attente", "Expédiée", "Livrée", "Annulée"])) {
            throw new \InvalidArgumentException("Statut invalide.");
        }
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

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(User $client): static
    {
        $this->client = $client;
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
