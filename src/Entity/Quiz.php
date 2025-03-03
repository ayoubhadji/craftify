<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Quiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private string $nom;

    #[ORM\Column(type: "integer")]
    private int $dureeMax;

    #[ORM\OneToMany(mappedBy: "quiz", targetEntity: Questions::class, cascade: ["persist", "remove"])]
    private Collection $questions;

    #[ORM\ManyToOne(targetEntity: Expedition::class, inversedBy: "quizzes")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Expedition $expedition = null;

    
    

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getDureeMax(): int
    {
        return $this->dureeMax;
    }

    public function setDureeMax(int $dureeMax): self
    {
        $this->dureeMax = $dureeMax;
        return $this;
    }

    public function getQuestions(): Collection
    {
        return $this->questions;
    }
    public function getExpedition(): ?Expedition
    {
        return $this->expedition;
    }

    public function setExpedition(?Expedition $expedition): self
    {
        $this->expedition = $expedition;
        return $this;
    }
}
