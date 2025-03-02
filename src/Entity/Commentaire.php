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

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $id_post = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "id_user_id", referencedColumnName: "id", nullable: false)] // ✅ Explicitly define column mapping
    private ?User $idUser = null;  // ✅ Use camelCase

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Le contenu du commentaire ne peut pas être vide.")]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: "La date du commentaire est obligatoire.")]
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

    public function getIdUser(): ?User  // ✅ Getter method for idUser
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static  // ✅ Setter method for idUser
    {
        $this->idUser = $idUser;
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
