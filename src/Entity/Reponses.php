<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Reponses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "text")]
    private string $reponse;

    #[ORM\ManyToOne(targetEntity: Questions::class, inversedBy: "reponses")]
    #[ORM\JoinColumn(nullable: false)]
    private Questions $question;

    #[ORM\Column(type: "boolean")]
private bool $isCorrect = false;

// Ajoute la méthode getter et setter pour 'isCorrect'
public function getIsCorrect(): bool
{
    return $this->isCorrect;
}

public function setIsCorrect(bool $isCorrect): self
{
    $this->isCorrect = $isCorrect;
    return $this;
}
    // Constructor to initialize the response
    public function __construct(string $reponse = '')
    {
        $this->reponse = $reponse;
    }
    public function getQuiz(): ?Quiz
    {
        return $this->question ? $this->question->getQuiz() : null;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReponse(): string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;
        return $this;
    }

    public function getQuestion(): Questions
    {
        return $this->question;
    }

    public function setQuestion(Questions $question): self
    {
        $this->question = $question;
        return $this;
    }
}
