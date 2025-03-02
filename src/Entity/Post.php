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

    #[ORM\Column(name: "type_post", length: 255)]
#[Assert\NotBlank(message: "Le type de post est obligatoire.")]
#[Assert\Choice(choices: ['image', 'vidéo', 'texte'], message: "Le type de post doit être 'image', 'vidéo' ou 'texte'.")]
private ?string $typePost = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Le contenu est obligatoire.")]
    #[Assert\Length(min: 10, minMessage: "Le contenu doit contenir au moins 10 caractères.")]
    private ?string $contenu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mediaUrl = null;

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
    private Collection $commentaires;  // ✅ Fix: Add the missing property

    /**
 * @var Collection<int, Reaction>
 */
#[ORM\OneToMany(targetEntity: Reaction::class, mappedBy: "id_post", orphanRemoval: true)]
private Collection $reactions;

    
public function __construct()
{
    $this->commentaires = new ArrayCollection();
    $this->reactions = new ArrayCollection();
    $this->nmb_like = 0;
    $this->date_publication = new \DateTime();
}

// Get all reactions
public function getReactions(): Collection
{
    return $this->reactions;
}

// Get total likes
public function getTotalLikes(): int
{
    return $this->reactions->filter(fn (Reaction $r) => $r->getType() === "like")->count();
}

// Get total dislikes
public function getTotalDislikes(): int
{
    return $this->reactions->filter(fn (Reaction $r) => $r->getType() === "dislike")->count();
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

    public function getNmbLike(): ?int
    {
        return $this->nmb_like;
    }

    public function setNmbLike(int $nmb_like): static
    {
        $this->nmb_like = $nmb_like;
        return $this;
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
        return $this->typePost;
    }
    
    public function setTypePost(string $typePost): static
    {
        $this->typePost = $typePost;
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
        return $this->mediaUrl;
    }

    public function setMediaUrl(?string $mediaUrl): self
    {
        $this->mediaUrl = $mediaUrl;
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

   

    /**
     
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    /**
     * ✅ Add a comment to the post
     */
    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setIdPost($this);
        }
        return $this;
    }

    /**
     * ✅ Remove a comment from the post
     */
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


