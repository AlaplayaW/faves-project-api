<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\Timer;
use App\Entity\User;
use App\Repository\FriendshipRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\NetworkController;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FriendshipRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['friend:read', 'time:read']],
    operations: [
        new GetCollection(
            openapiContext: [
                'security' => [['JWT' => []]]
            ],
        ),
        new GetCollection(
            name: 'get_all_friends_by_network',
            uriTemplate: '/network/all-friends',
            controller: NetworkController::class,
            openapiContext: [
                'summary' => "Récupère la liste d'amis en attente ou acceptées de l'utilisateur connecté.",
                'security' => [['JWT' => []]]
            ],
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['friend:read', 'friendsByNetwork:read']],
            name: 'get_friends_by_network',
            uriTemplate: '/network/friends',
            controller: NetworkController::class,
            openapiContext: [
                'summary' => "Récupère la liste des amis validés de l'utilisateur actuellement connecté",
                'security' => [['JWT' => []]]
            ],
        ),
    ]
)]



#[ORM\Index(name: "idx_friend_requester_id", columns: ["friend_requester_id"])]
#[ORM\Index(name: "idx_friend_accepter_id", columns: ["friend_accepter_id"])]
// #[ApiFilter(SearchFilter::class, properties: ['friend.id', 'user.id'])]
// #[ApiFilter(BooleanFilter::class, properties: ['isAccepted'])]
class Friendship
{
    use Timer;

    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['friend:read'])]
    private ?int $id = null;

    #[Groups(['friend:read'])]
    #[ORM\Column(type: 'string')]
    private string $status = self::STATUS_PENDING;

    #[Groups(['friend:read'])]
    #[ORM\Column(type: 'datetime', nullable: true)]
    private $requestDate = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $acceptanceDate = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $rejectionDate = null;

    #[Groups(['friend:read'])]
    #[ORM\ManyToOne(inversedBy: 'friendRequesters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $friendRequester = null;

    #[Groups(['friend:read'])]
    #[ORM\ManyToOne(inversedBy: 'friendAccepters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $friendAccepter = null;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        if (!in_array($status, [self::STATUS_PENDING, self::STATUS_ACCEPTED, self::STATUS_DECLINED])) {
            throw new \InvalidArgumentException("Invalid status");
        }

        $this->status = $status;

        return $this;
    }

    public function getRequestDate(): ?\DateTimeInterface
    {
        return $this->requestDate;
    }

    public function setRequestDate(?\DateTimeInterface $requestDate): self
    {
        $this->requestDate = $requestDate;

        if ($this->requestDate === null) {
            $this->requestDate = $this->createdAt;
        }

        return $this;
    }

    public function getAcceptanceDate(): ?\DateTimeInterface
    {
        return $this->acceptanceDate;
    }

    public function setAcceptanceDate(?\DateTimeInterface $acceptanceDate): self
    {
        $this->acceptanceDate = $acceptanceDate;

        if ($this->status === self::STATUS_ACCEPTED && $this->acceptanceDate === null) {
            $this->acceptanceDate = $this->updatedAt;
        }

        return $this;
    }

    public function getRejectionDate(): ?\DateTimeInterface
    {
        return $this->rejectionDate;
    }

    public function setRejectionDate(?\DateTimeInterface $rejectionDate): self
    {
        $this->rejectionDate = $rejectionDate;

        if ($this->status === self::STATUS_DECLINED && $this->rejectionDate === null) {
            $this->rejectionDate = $this->updatedAt;
        }

        return $this;
    }

    public function getFriendRequester(): ?User
    {
        return $this->friendRequester;
    }

    public function setFriendRequester(?User $friendRequester): self
    {
        $this->friendRequester = $friendRequester;

        return $this;
    }

    public function getFriendAccepter(): ?User
    {
        return $this->friendAccepter;
    }

    public function setFriendAccepter(?User $friendAccepter): self
    {
        $this->friendAccepter = $friendAccepter;

        return $this;
    }
}
