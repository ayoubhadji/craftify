<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "L'utilisateur est obligatoire.")]
    private ?User $id_user = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le type de post est obligatoire.")]
    #[Assert\Choice(choices: ['image', 'vidéo', 'texte'], message: "Le type de post doit être 'image', 'vidéo' ou 'texte'.")]
    private ?string $type_post = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Le contenu est obligatoire.")]
    #[Assert\Length(min: 10, minMessage: "Le contenu doit contenir au moins 10 caractères.")]
    private ?string $contenu = null;

    #[ORM\Column(length: 255)]
    #[Assert\Url(message: "L'URL du média doit être valide.")]
    #[Assert\NotBlank(message: "L'URL du média est obligatoire.")]
    private ?string $media_url = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: "La date de publication est obligatoire.")]
    #[Assert\Type(type: \DateTimeInterface::class, message: "La date de publication doit être une date valide.")]
    private ?\DateTimeInterface $date_publication = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La tranche d'âge est obligatoire.")]
    #[Assert\Choice(choices: ['enfant', 'adolescent', 'adulte'], message: "La tranche d'âge doit être 'enfant', 'adolescent' ou 'adulte'.")]
    private ?string $tranche_dage = null;

    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero(message: "Le nombre de likes doit être un entier positif ou nul.")]
    private ?int $nmb_like = null;

    /**
     * @var Collection<int, Commentaire>
     */
    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'id_post', orphanRemoval: true)]
    private Collection $commentaires;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): static
    {
        $this->id_user = $id_user;
        return $this;
    }

    public function getTypePost(): ?string
    {
        return $this->type_post;
    }

    public function setTypePost(string $type_post): static
    {
        $this->type_post = $type_post;
        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;
        return $this;
    }

    public function getMediaUrl(): ?string
    {
        return $this->media_url;
    }

    public function setMediaUrl(string $media_url): static
    {
        $this->media_url = $media_url;
        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDatePublication(\DateTimeInterface $date_publication): static
    {
        $this->date_publication = $date_publication;
        return $this;
    }

    public function getTrancheDage(): ?string
    {
        return $this->tranche_dage;
    }

    public function setTrancheDage(string $tranche_dage): static
    {
        $this->tranche_dage = $tranche_dage;
        return $this;
    }

    public function getNmbLike(): ?int
    {
        return $this->nmb_like;
    }

    public function setNmbLike(?int $nmb_like): static
    {
        $this->nmb_like = $nmb_like;
        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setIdPost($this);
        }
        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            if ($commentaire->getIdPost() === $this) {
                $commentaire->setIdPost(null);
            }
        }
        return $this;
    }
}
