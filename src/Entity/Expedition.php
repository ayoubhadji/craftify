<?php

namespace App\Entity;

use App\Repository\ExpeditionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExpeditionRepository::class)]
class Expedition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de l'expédition est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $nomExpedition = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "L'univers est obligatoire.")]
    #[Assert\Length(
        max: 100,
        maxMessage: "L'univers ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $univers = null;

    #[ORM\Column(name: "cart_tresor_url", length: 255)]
    #[Assert\NotBlank(message: "L'URL de la carte au trésor est obligatoire.")]
    #[Assert\Url(message: "L'URL de la carte doit être valide.")]
    private ?string $cart_tresor_url = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Les quêtes disponibles doivent être renseignées.")]
    private ?string $quetesDispo = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "L'objet magique doit être renseigné.")]
    private ?string $objetMagique = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Les gardiens artisanaux doivent être renseignés.")]
    private ?string $gardien_artisanaux = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "La durée mystique est obligatoire.")]
    #[Assert\Length(
        max: 50,
        maxMessage: "La durée mystique ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $dureeMystique = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Le secret caché doit être renseigné.")]
    private ?string $secretCache = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La relique finale est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom de la relique finale ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $reliqueFinale = null;

    /**
     * @var Collection<int, Aventurier>
     */
    #[ORM\ManyToMany(targetEntity: Aventurier::class, inversedBy: 'expeditions')]
    #[ORM\JoinTable(name: 'aventurier_expedition')]
    private Collection $aventuriers;

    public function __construct()
    {
        $this->aventuriers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomExpedition(): ?string
    {
        return $this->nomExpedition;
    }

    public function setNomExpedition(string $nomExpedition): static
    {
        $this->nomExpedition = $nomExpedition;
        return $this;
    }

    public function getUnivers(): ?string
    {
        return $this->univers;
    }

    public function setUnivers(string $univers): static
    {
        $this->univers = $univers;
        return $this;
    }

    public function getCartTresorUrl(): ?string
    {
        return $this->cart_tresor_url;
    }

    public function setCartTresorUrl(string $cart_tresor_url): self
    {
        $this->cart_tresor_url = $cart_tresor_url;
        return $this;
    }

    public function getQuetesDispo(): ?string
    {
        return $this->quetesDispo;
    }

    public function setQuetesDispo(string $quetesDispo): static
    {
        $this->quetesDispo = $quetesDispo;
        return $this;
    }

    public function getObjetMagique(): ?string
    {
        return $this->objetMagique;
    }

    public function setObjetMagique(string $objetMagique): static
    {
        $this->objetMagique = $objetMagique;
        return $this;
    }

    public function getGardienArtisanaux(): ?string
    {
        return $this->gardien_artisanaux;
    }

    public function setGardienArtisanaux(string $gardien_artisanaux): static
    {
        $this->gardien_artisanaux = $gardien_artisanaux;
        return $this;
    }

    public function getDureeMystique(): ?string
    {
        return $this->dureeMystique;
    }

    public function setDureeMystique(string $dureeMystique): static
    {
        $this->dureeMystique = $dureeMystique;
        return $this;
    }

    public function getSecretCache(): ?string
    {
        return $this->secretCache;
    }

    public function setSecretCache(string $secretCache): static
    {
        $this->secretCache = $secretCache;
        return $this;
    }

    public function getReliqueFinale(): ?string
    {
        return $this->reliqueFinale;
    }

    public function setReliqueFinale(string $reliqueFinale): static
    {
        $this->reliqueFinale = $reliqueFinale;
        return $this;
    }

    /**
     * @return Collection<int, Aventurier>
     */
    public function getAventuriers(): Collection
    {
        return $this->aventuriers;
    }

    public function addAventurier(Aventurier $aventurier): static
    {
        if (!$this->aventuriers->contains($aventurier)) {
            $this->aventuriers->add($aventurier);
            $aventurier->addExpedition($this);
        }

        return $this;
    }

    public function removeAventurier(Aventurier $aventurier): static
    {
        if ($this->aventuriers->removeElement($aventurier)) {
            $aventurier->removeExpedition($this);
        }

        return $this;
    }
}
