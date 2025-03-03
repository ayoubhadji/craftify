<?php

namespace App\Entity;

use App\Repository\ExpeditionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ApiResource(
    normalizationContext: ['groups' => ['expedition:read']],
    denormalizationContext: ['groups' => ['expedition:write']]
)]
#[ORM\Entity(repositoryClass: ExpeditionRepository::class)]
class Expedition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre de l'expédition est obligatoire.")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Le titre doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le titre ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "L'objectif de l'expédition est obligatoire.")]
    private ?string $objectif = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date de début est obligatoire.")]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date de fin est obligatoire.")]
    #[Assert\GreaterThan(propertyPath: "dateDebut", message: "La date de fin doit être après la date de début.")]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\ManyToOne(targetEntity: Aventurier::class, inversedBy: 'expeditions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Aventurier $aventurier = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $videoUrl = null;

    #[ORM\OneToMany(mappedBy: "expedition", targetEntity: Quiz::class)]
    private Collection $quizzes;

    // Add missing property
    #[ORM\ManyToMany(targetEntity: Aventurier::class, inversedBy: 'expeditions')]
    private Collection $aventuriers;

    public function __construct()
    {
        $this->quizzes = new ArrayCollection();
        $this->aventuriers = new ArrayCollection();
    }

    public function getQuizzes(): Collection
    {
        return $this->quizzes;
    }

    public function getVideoUrl(): ?string
    {
        return $this->videoUrl;
    }

    public function setVideoUrl(?string $videoUrl): self
    {
        $this->videoUrl = $videoUrl;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
        return $this;
    }

    public function getObjectif(): ?string
    {
        return $this->objectif;
    }

    public function setObjectif(string $objectif): static
    {
        $this->objectif = $objectif;
        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getAventurier(): ?Aventurier
    {
        return $this->aventurier;
    }

    public function setAventurier(Aventurier $aventurier): static
    {
        $this->aventurier = $aventurier;
        return $this;
    }

    // Methods to add/remove aventurier (if applicable for Many-to-Many)
    public function addAventurier(Aventurier $aventurier): self
    {
        if (!$this->aventuriers->contains($aventurier)) {
            $this->aventuriers[] = $aventurier;
        }
        return $this;
    }

    public function removeAventurier(Aventurier $aventurier): self
    {
        $this->aventuriers->removeElement($aventurier);
        return $this;
    }
}
