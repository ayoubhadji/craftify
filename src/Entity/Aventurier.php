<?php

namespace App\Entity;

use App\Repository\AventurierRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: AventurierRepository::class)]
#[UniqueEntity(fields: ["email"], message: "L'email est déjà utilisé.")]
class Aventurier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    #[Assert\Length(min: 2, max: 255, minMessage: "Le nom doit contenir au moins 2 caractères.")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
    #[Assert\Length(min: 2, max: 255, minMessage: "Le prénom doit contenir au moins 2 caractères.")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "L'email doit être valide.")]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "La date d'inscription est obligatoire.")]
    private ?\DateTimeInterface $dateInscription = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le statut est obligatoire.")]
    #[Assert\Choice(choices: ["actif", "inactif", "en pause"], message: "Le statut doit être 'actif', 'inactif' ou 'en pause'.")]
    private ?string $statut = null;

    #[ORM\ManyToMany(targetEntity: Expedition::class, inversedBy: 'aventuriers')]
    #[ORM\JoinTable(name: 'aventurier_expedition')]
    private Collection $expeditions;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $phoneNumber = null;

    // Constructor
    public function __construct()
    {
        $this->expeditions = new ArrayCollection();
        $this->dateInscription = new \DateTime(); // Définit la date actuelle
    }

    // Getter and Setter methods

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
        $this->nom = trim($nom);
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = trim($prenom);
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = trim($email);
        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): static
    {
        $this->dateInscription = $dateInscription;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = trim($statut);
        return $this;
    }

    // Get the expeditions (ManyToMany relationship)
    public function getExpeditions(): Collection
    {
        return $this->expeditions;
    }

    // Add an expedition to the collection
    public function addExpedition(Expedition $expedition): self
    {
        if (!$this->expeditions->contains($expedition)) {
            $this->expeditions[] = $expedition;
            $expedition->addAventurier($this);  
        }
        return $this;
    }

    // Remove an expedition from the collection
    public function removeExpedition(Expedition $expedition): self
    {
        if ($this->expeditions->contains($expedition)) {
            $this->expeditions->removeElement($expedition);
            $expedition->removeAventurier($this);  // Synchroniser l'autre côté de la relation
        }
        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }
}
