<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\Timer;
use App\Entity\User;
use App\Repository\FriendshipRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\FriendshipController;
use App\Controller\GetUserFriends;
use App\Controller\NetworkController;
use App\Filters\FriendsFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FriendshipRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['friends']],
    operations: [
        new Get(),
        new GetCollection(),
        new GetCollection(
            name: 'get_friends_by_user',
            uriTemplate: '/api/network/friends',
            controller: NetworkController::class,
        ),
    ]
)]

#[ORM\Index(name: "idx_friendship_requester_id", columns: ["friendship_requester_id"])]
#[ORM\Index(name: "idx_friendship_accepter_id", columns: ["friendship_accepter_id"])]
// #[ApiFilter(SearchFilter::class, properties: ['friendshipAccepter.id', 'friendshipRequester.id'])]
// #[ApiFilter(BooleanFilter::class, properties: ['isAccepted'])]
class Friendship
{
    use Timer;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read', 'user:write'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'friendshipRequests')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['friends'])]
    private ?User $friendshipRequester = null;

    #[ORM\ManyToOne(inversedBy: 'friendshipAccepters')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['friends'])]
    private ?User $friendshipAccepter = null;

    #[Groups(['user:read', 'friends'])]
    #[ORM\Column(type: 'boolean')]
    private bool $isAccepted = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFriendshipRequester(): ?User
    {
        return $this->friendshipRequester;
    }

    public function setFriendshipRequester(?User $friendshipRequester): self
    {
        $this->friendshipRequester = $friendshipRequester;

        return $this;
    }

    public function getFriendshipAccepter(): ?User
    {
        return $this->friendshipAccepter;
    }

    public function setFriendshipAccepter(?User $friendshipAccepter): self
    {
        $this->friendshipAccepter = $friendshipAccepter;

        return $this;
    }

    public function getIsAccepted(): bool
    {
        return $this->isAccepted;
    }

    public function setIsAccepted(bool $isAccepted): void
    {
        $this->isAccepted = $isAccepted;
    }
}
