<?php

namespace App\Entity;

use App\Repository\AventurierExpeditionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AventurierExpeditionRepository::class)]
class AventurierExpedition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Aventurier::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Aventurier $aventurier = null;

    #[ORM\ManyToOne(targetEntity: Expedition::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Expedition $expedition = null;

    

    #[ORM\Column(type: 'string', length: 20, options: ["default" => "en cours"])]
    private string $status = 'en cours'; // 'en cours', 'terminé'

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateValidation = null;

    // Getter et Setter pour id
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter et Setter pour aventurier
    public function getAventurier(): ?Aventurier
    {
        return $this->aventurier;
    }

    public function setAventurier(?Aventurier $aventurier): self
    {
        $this->aventurier = $aventurier;

        return $this;
    }

    // Getter et Setter pour expedition
    public function getExpedition(): ?Expedition
    {
        return $this->expedition;
    }

    public function setExpedition(?Expedition $expedition): self
    {
        $this->expedition = $expedition;

        return $this;
    }

   

    // Getter et Setter pour status
    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    // Getter et Setter pour dateValidation
    public function getDateValidation(): ?\DateTimeInterface
    {
        return $this->dateValidation;
    }

    public function setDateValidation(?\DateTimeInterface $dateValidation): self
    {
        $this->dateValidation = $dateValidation;

        return $this;
    }
}
