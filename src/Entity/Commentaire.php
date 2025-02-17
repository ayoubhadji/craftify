<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[Assert\NotNull(message: "Le commentaire doit être associé à un post.")]
    private ?Post $id_post = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[Assert\NotNull(message: "Le commentaire doit être associé à un utilisateur.")]
    private ?User $id_user = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Le contenu du commentaire ne peut pas être vide.")]
    #[Assert\Length(
        min: 3,
        minMessage: "Le commentaire doit contenir au moins {{ limit }} caractères."
    )]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: "La date du commentaire est obligatoire.")]
    #[Assert\Type("\DateTimeInterface", message: "La date du commentaire doit être une date valide.")]
    private ?\DateTimeInterface $date_commentaire = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Le nombre de likes ne peut pas être nul.")]
    #[Assert\PositiveOrZero(message: "Le nombre de likes ne peut pas être négatif.")]
    private ?int $nmb_like = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPost(): ?Post
    {
        return $this->id_post;
    }

    public function setIdPost(?Post $id_post): static
    {
        $this->id_post = $id_post;
        return $this;
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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;
        return $this;
    }

    public function getDateCommentaire(): ?\DateTimeInterface
    {
        return $this->date_commentaire;
    }

    public function setDateCommentaire(\DateTimeInterface $date_commentaire): static
    {
        $this->date_commentaire = $date_commentaire;
        return $this;
    }

    public function getNmbLike(): ?int
    {
        return $this->nmb_like;
    }

    public function setNmbLike(int $nmb_like): static
    {
        $this->nmb_like = $nmb_like;
        return $this;
    }
}
