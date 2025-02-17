<?php

namespace App\Entity;

use App\Repository\SliderItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SliderItemRepository::class)]
class SliderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $imagePath = null;

    #[ORM\Column(length: 255)]
    private ?string $altText = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column]
    private ?int $position = null;
    

    #[ORM\ManyToOne(targetEntity: Foire::class, inversedBy:'sliderItems')]
    private ?Foire $foire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): static
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getAltText(): ?string
    {
        return $this->altText;
    }

    public function setAltText(string $altText): static
    {
        $this->altText = $altText;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getFoire(): ?Foire
    {
        return $this->foire;
    }

    public function setFoire(?Foire $foire): static
    {
        $this->foire = $foire;

        return $this;
    }
}
