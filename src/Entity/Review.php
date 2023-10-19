<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\Timer;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Controller\NetworkController;
use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['review:read']],
    denormalizationContext: ['groups' => ['review:write']],
	security: 'is_granted("ROLE_USER")',

    operations: [
        new Get(),
        new Post(),
        new GetCollection(),
        new GetCollection(
			normalizationContext: ['groups' => ['review:read', 'reviewsByNetwork:read']],
			name: 'get_reviews_by_network',
			uriTemplate: '/network/reviews',
			controller: NetworkController::class,
			openapiContext: ['summary' => "Récupère la liste des critiques des amis de l'utilisateur actuellement connecté"],
	),
    ]
    )]
class Review
{
    use Timer;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['review:read'])]
    private ?int $id = null;

    #[Groups(['review:read', 'booksByNetwork:read', 'review:write'])]
    #[ORM\Column]
    private ?int $rating = null;

    #[Groups(['review:read', 'review:write', 'booksByNetwork:read'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[Groups(['reviewsByNetwork:read', 'review:write'])]
    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    #[Groups(['reviewsByNetwork:read', 'booksByNetwork:read', 'review:write'])]
    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
