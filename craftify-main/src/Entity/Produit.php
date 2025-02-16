<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[Vich\Uploadable]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du produit est obligatoire.")]
    #[Assert\Length(min: 3, max: 255, minMessage: "Le nom doit contenir au moins 3 caractères.")]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 1000, maxMessage: "La description ne peut pas dépasser 1000 caractères.")]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Assert\NotBlank(message: "Le prix est obligatoire.")]
    #[Assert\Positive(message: "Le prix doit être supérieur à zéro.")]
    private ?float $prix = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\PositiveOrZero(message: "Le stock ne peut pas être négatif.")]
    private ?int $stock = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img_url = null;

    #[Vich\UploadableField(mapping: "produit_images", fileNameProperty: "img_url")]
    #[Assert\File(
        maxSize: "100M",
        mimeTypes: ["image/jpeg", "image/png", "image/webp"],
        mimeTypesMessage: "Veuillez télécharger une image valide (JPEG, PNG, WebP)."
    )]
    private ?File $img_file = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?User $id_artisan = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    // Getters and setters

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

    public function setDescription(?string $description): static
    {
        $this->description = $description;
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

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): static
    {
        $this->stock = $stock;
        return $this;
    }

    public function getImgUrl(): ?string
    {
        return $this->img_url;
    }

    public function setImgUrl(?string $img_url): self
    {
        $this->img_url = $img_url;
        return $this;
    }

    public function getImgFile(): ?File
    {
        return $this->img_file;
    }

    public function setImgFile(?File $img_file = null): void
    {
        $this->img_file = $img_file;
        if ($img_file) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getIdArtisan(): ?User
    {
        return $this->id_artisan;
    }

    public function setIdArtisan(?User $id_artisan): static
    {
        $this->id_artisan = $id_artisan;
        return $this;
    }
}
