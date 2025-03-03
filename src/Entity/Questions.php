<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Questions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "text")]
    private string $question;

    #[ORM\Column(type: "text")]
    private string $reponseCorrecte;

    #[ORM\ManyToOne(targetEntity: Quiz::class, inversedBy: "questions")]
    #[ORM\JoinColumn(nullable: false)]
    private Quiz $quiz;

    #[ORM\OneToMany(mappedBy: "question", targetEntity: Reponses::class)]
    private iterable $reponses;

    // Constructor to initialize the reponses property
    public function __construct()
    {
        $this->reponses = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;
        return $this;
    }

    public function getReponseCorrecte(): string
    {
        return $this->reponseCorrecte;
    }

    public function setReponseCorrecte(string $reponseCorrecte): self
    {
        $this->reponseCorrecte = $reponseCorrecte;
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

    public function getReponses(): iterable
    {
        return $this->reponses;
    }

    public function setReponses(iterable $reponses): self
    {
        $this->reponses = $reponses;
        return $this;
    }
}
