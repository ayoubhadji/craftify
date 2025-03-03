<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity] 
class AventurierQuiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Aventurier::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Aventurier $aventurier;

    #[ORM\ManyToOne(targetEntity: Quiz::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Quiz $quiz;

    #[ORM\Column(type: "integer")]
    private ?int $score=0 ; 

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $datePassage;

    #[ORM\Column(type: "string", length: 255)]
    private string $statut;

    #[ORM\Column(type: "boolean")]
    private bool $certificatDelivre = false;
// In your entity (App\Entity\AventurierQuiz.php)
public function __construct()
{
    $this->score ;
}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAventurier(): Aventurier
    {
        return $this->aventurier;
    }

    public function setAventurier(Aventurier $aventurier): self
    {
        $this->aventurier = $aventurier;
        return $this;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(Quiz $quiz): self
    {
        $this->quiz = $quiz;
        return $this;
    }

    public function getScore(): int
    {
        return $this->score ?? 0;  // Si $score est null, renvoyer 0
    }

    public function setScore(int $score): self
    {
        $this->score = $score;
        return $this;
    }

    public function getDatePassage(): \DateTimeInterface
    {
        return $this->datePassage;
    }

    public function setDatePassage(\DateTimeInterface $datePassage): self
    {
        $this->datePassage = $datePassage;
        return $this;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function isCertificatDelivre(): bool
    {
        return $this->certificatDelivre;
    }

    public function setCertificatDelivre(bool $certificatDelivre): self
    {
        $this->certificatDelivre = $certificatDelivre;
        return $this;
    }
}

