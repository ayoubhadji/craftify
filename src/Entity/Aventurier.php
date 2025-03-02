<?php

namespace App\Entity;

use App\Repository\AventurierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AventurierRepository::class)]
class Aventurier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de code est obligatoire.")]
    #[Assert\Length(min: 3, max: 255, minMessage: "Le nom de code doit contenir au moins 3 caractères.")]
    private ?string $nom_code = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Les quêtes terminées doivent être renseignées.")]
    private ?string $quetes_terminees = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "L'artefact possédé est obligatoire.")]
    private ?string $artefact_possede = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le compagnon créatif est obligatoire.")]
    private ?string $compagne_creatif = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le badge légendaire est obligatoire.")]
    private ?string $badge_legendaire = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Le signe distinctif est obligatoire.")]
    private ?string $signe_distinctif = null;

    /**
     * @var Collection<int, Expedition>
     */
    #[ORM\ManyToMany(targetEntity: Expedition::class, inversedBy: 'aventuriers')]
    private Collection $expeditions;

    public function __construct()
    {
        $this->expeditions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCode(): ?string
    {
        return $this->nom_code;
    }

    public function setNomCode(string $nom_code): static
    {
        $this->nom_code = $nom_code;
        return $this;
    }

    public function getQuetesTerminees(): ?string
    {
        return $this->quetes_terminees;
    }

    public function setQuetesTerminees(string $quetes_terminees): static
    {
        $this->quetes_terminees = $quetes_terminees;
        return $this;
    }

    public function getArtefactPossede(): ?string
    {
        return $this->artefact_possede;
    }

    public function setArtefactPossede(string $artefact_possede): static
    {
        $this->artefact_possede = $artefact_possede;
        return $this;
    }

    public function getCompagneCreatif(): ?string
    {
        return $this->compagne_creatif;
    }

    public function setCompagneCreatif(string $compagne_creatif): static
    {
        $this->compagne_creatif = $compagne_creatif;
        return $this;
    }

    public function getBadgeLegendaire(): ?string
    {
        return $this->badge_legendaire;
    }

    public function setBadgeLegendaire(string $badge_legendaire): static
    {
        $this->badge_legendaire = $badge_legendaire;
        return $this;
    }

    public function getSigneDistinctif(): ?string
    {
        return $this->signe_distinctif;
    }

    public function setSigneDistinctif(string $signe_distinctif): static
    {
        $this->signe_distinctif = $signe_distinctif;
        return $this;
    }

    /**
     * @return Collection<int, Expedition>
     */
    public function getExpeditions(): Collection
    {
        return $this->expeditions;
    }

    public function addExpedition(Expedition $expedition): static
    {
        if (!$this->expeditions->contains($expedition)) {
            $this->expeditions->add($expedition);
        }
        return $this;
    }

    public function removeExpedition(Expedition $expedition): static
    {
        $this->expeditions->removeElement($expedition);
        return $this;
    }
}
