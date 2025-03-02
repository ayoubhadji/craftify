<?php


namespace App\Entity;

use App\Repository\ReactionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReactionRepository::class)]
#[ORM\UniqueConstraint(name: "unique_reaction", columns: ["id_user", "id_post"])]
class Reaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "reactions")]
#[ORM\JoinColumn(name: "id_user", referencedColumnName: "id", nullable: false)]
private ?User $id_user = null;

    

#[ORM\ManyToOne(targetEntity: Post::class, inversedBy: "reactions")]
#[ORM\JoinColumn(name: "id_post", referencedColumnName: "id", nullable: false)]
private ?Post $id_post = null;

    #[ORM\Column(type: "string", length: 10)]
    private ?string $type = null; // "like" or "dislike"

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(User $user): static
    {
        $this->id_user = $user;
        return $this;
    }

    public function getIdPost(): ?Post
    {
        return $this->id_post;
    }

    public function setIdPost(Post $post): static
    {
        $this->id_post = $post;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }
}