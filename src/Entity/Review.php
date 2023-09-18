<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\Timer;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
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
    operations: [
        new Get(),
        new Post(),
        // new Put(),
        // new Delete(),
        new GetCollection(),
        // new GetCollection(
        //     name: 'api_users_reviews',
        //     uriTemplate: '/reviews/users/',
        //     // controller: GetReviewsByUsers::class,
        //     openapiContext: [
        //         'summary' => 'Get all reviews of a user list',
        //         'description' => '# Get all reviews of a  list',
        //     ],
        // ),
        // new GetCollection(
        //     name: 'api_user_friends_demands',
        //     uriTemplate: '/users/{id}/friendship-demands/recieved',
        //     openapiContext: [
        //         'summary' => 'Get all friends of a user',
        //         'description' => '# Get all friends of a user'
        //     ],
        // ),
    ]
    )]
// #[ApiFilter(SearchFilter::class, properties: ['user.id'])]
class Review
{
    use Timer;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['review:read'])]
    private ?int $id = null;

    #[Groups(['user:read', 'review:read'])]
    #[ORM\Column]
    private ?int $rating = null;

    #[Groups(['user:read', 'review:read'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user:read', 'review:read'])]
    private ?Book $book = null;

    #[Groups(['user:read', 'review:read'])]
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
