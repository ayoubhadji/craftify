<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de l'événement est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "La description de l'événement est obligatoire.")]
    #[Assert\Length(min: 10, minMessage: "La description doit contenir au moins {{ limit }} caractères.")]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: "La date de début est obligatoire.")]
    #[Assert\Type("\DateTimeInterface", message: "La date de début doit être valide.")]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: "La date de fin est obligatoire.")]
    #[Assert\Type("\DateTimeInterface", message: "La date de fin doit être valide.")]
    #[Assert\GreaterThan(
        propertyPath: "date_debut",
        message: "La date de fin doit être postérieure à la date de début."
    )]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le lieu de l'événement est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le lieu ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $lieu = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "La capacité de l'événement est obligatoire.")]
    #[Assert\Positive(message: "La capacité doit être un nombre positif.")]
    private ?int $capacite = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le type d'événement est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le type d'événement ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $type_evenement = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Le prix de l'événement est obligatoire.")]
    #[Assert\PositiveOrZero(message: "Le prix ne peut pas être négatif.")]
    private ?float $prix = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'organisateur de l'événement est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom de l'organisateur ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $organisateur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'image de l'événement est obligatoire.")]
    #[Assert\Url(message: "L'image doit être une URL valide.")]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: "La date de création est obligatoire.")]
    #[Assert\Type("\DateTimeInterface", message: "La date de création doit être valide.")]
    private ?\DateTimeInterface $date_creation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;
        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;
        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): static
    {
        $this->capacite = $capacite;
        return $this;
    }

    public function getTypeEvenement(): ?string
    {
        return $this->type_evenement;
    }

    public function setTypeEvenement(string $type_evenement): static
    {
        $this->type_evenement = $type_evenement;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;
        return $this;
    }

    public function getOrganisateur(): ?string
    {
        return $this->organisateur;
    }

    public function setOrganisateur(string $organisateur): static
    {
        $this->organisateur = $organisateur;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): static
    {
        $this->date_creation = $date_creation;
        return $this;
    }
}
