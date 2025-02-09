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
    private ?string $nom_expedition = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "L'univers est obligatoire.")]
    #[Assert\Length(
        max: 100,
        maxMessage: "L'univers ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $univers = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'URL de la carte au trésor est obligatoire.")]
    #[Assert\Url(message: "L'URL de la carte doit être valide.")]
    private ?string $cart_tresor_url = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Les quêtes disponibles doivent être renseignées.")]
    private ?string $quetes_dispo = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "L'objet magique doit être renseigné.")]
    private ?string $objet_magique = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Les gardiens artisanaux doivent être renseignés.")]
    private ?string $gardien_artisanaux = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "La durée mystique est obligatoire.")]
    #[Assert\Length(
        max: 50,
        maxMessage: "La durée mystique ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $duree_mystique = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Le secret caché doit être renseigné.")]
    private ?string $secret_cache = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La relique finale est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom de la relique finale ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $relique_final = null;

    /**
     * @var Collection<int, Aventurier>
     */
    #[ORM\ManyToMany(targetEntity: Aventurier::class, mappedBy: 'id_expedition')]
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
        return $this->nom_expedition;
    }

    public function setNomExpedition(string $nom_expedition): static
    {
        $this->nom_expedition = $nom_expedition;
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

    public function setCartTresorUrl(string $cart_tresor_url): static
    {
        $this->cart_tresor_url = $cart_tresor_url;
        return $this;
    }

    public function getQuetesDispo(): ?string
    {
        return $this->quetes_dispo;
    }

    public function setQuetesDispo(string $quetes_dispo): static
    {
        $this->quetes_dispo = $quetes_dispo;
        return $this;
    }

    public function getObjetMagique(): ?string
    {
        return $this->objet_magique;
    }

    public function setObjetMagique(string $objet_magique): static
    {
        $this->objet_magique = $objet_magique;
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
        return $this->duree_mystique;
    }

    public function setDureeMystique(string $duree_mystique): static
    {
        $this->duree_mystique = $duree_mystique;
        return $this;
    }

    public function getSecretCache(): ?string
    {
        return $this->secret_cache;
    }

    public function setSecretCache(string $secret_cache): static
    {
        $this->secret_cache = $secret_cache;
        return $this;
    }

    public function getReliqueFinal(): ?string
    {
        return $this->relique_final;
    }

    public function setReliqueFinal(string $relique_final): static
    {
        $this->relique_final = $relique_final;
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
            $aventurier->addIdExpedition($this);
        }

        return $this;
    }

    public function removeAventurier(Aventurier $aventurier): static
    {
        if ($this->aventuriers->removeElement($aventurier)) {
            $aventurier->removeIdExpedition($this);
        }

        return $this;
    }
}
