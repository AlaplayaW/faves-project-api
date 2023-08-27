<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\GetUserFriends;
use App\Entity\Traits\Timer;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    normalizationContext: ['groups' => ['user:read', 'friends']],
    // denormalizationContext: ['groups' => ['user:write', 'item:write']],
    operations: [
            new Get(),
            // new GetCollection(),
            // new Post(),
            // new Patch(),
            // new Put(),
            // new Delete(),
            // new GetCollection(
            //     name: 'api_user_friends',
            //     uriTemplate: '/users/{id}/friends',
            //     controller: GetUserFriends::class,
            //     openapiContext: [
            //         'summary' => 'Get all requester of a user',
            //         'description' => '# Get all friends of a user',
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
// #[ApiResource(
//     uriTemplate: '/users/{id}/friends',
//     uriVariables: [
//         'userId' => new Link(
//             fromClass: User::class,
//             fromProperty: 'id'
//         )
//     ], 
//     operations: [new GetCollection()]
// )]
// api/friendships/{userId}/sent-requests
// api/friendships/{userId}/recieved-requests
#[ORM\Index(name: "idx_user_id", columns: ["id"])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use Timer;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    // #[Groups(['user:read', 'friends', 'item:read', 'item:write'])]
    private ?int $id = null;

    // #[Groups(['user:read', 'user:write', 'item:write'])]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    // #[Groups(['user:read', 'user:write'])]
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[Groups(['user:write'])]
    #[ORM\Column]
    private ?string $password = null;

    #[Groups(['user:read', 'user:write', 'friends'])]
    #[ORM\Column(length: 100)]
    private ?string $firstName = null;

    #[Groups(['user:read', 'user:write'])]
    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    #[Groups(['user:read', 'user:write', 'item:read', 'review:read'])]
    #[ORM\Column(length: 100)]
    private ?string $userName = null;

    #[Groups(['user:read', 'user:write'])]
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $birthDate = null;

    #[Groups(['user:read', 'user:write'])]
    #[ORM\Column(length: 25, nullable: true)]
    private ?string $phone = null;

    #[Groups(['user:read', 'item:read', 'review:read'])]
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Media $media = null;

    // #[Groups(['user:read', 'user:write'])]
    #[ORM\OneToMany(mappedBy: 'friendshipRequester', targetEntity: Friendship::class, orphanRemoval: true)]
    private Collection $friendshipRequests;

    // #[Groups(['user:read', 'user:write'])]
    #[ORM\OneToMany(mappedBy: 'friendshipAccepter', targetEntity: Friendship::class, orphanRemoval: true)]
    private Collection $friendshipAccepters;

    // #[Groups(['user:read', 'user:write'])]
    #[ORM\OneToMany(mappedBy: 'postedBy', targetEntity: Item::class)]
    private Collection $items;

    // #[Groups(['user:read', 'user:write'])]
    #[ORM\OneToMany(mappedBy: 'postedBy', targetEntity: Review::class, orphanRemoval: true)]
    private Collection $reviews;


    // public function __construct()
    // {
    //     $this->friendshipRequests = new ArrayCollection();
    //     $this->friendshipAccepters = new ArrayCollection();
    //     $this->items = new ArrayCollection();
    //     $this->reviews = new ArrayCollection();
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }
    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getmedia(): ?Media
    {
        return $this->media;
    }

    public function setmedia(?Media $media): self
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return Collection<int, Friendship>
     */
    public function getFriendshipRequests(): Collection
    {
        return $this->friendshipRequests;
    }

    public function addFriendshipRequest(Friendship $friendshipRequest): self
    {
        if (!$this->friendshipRequests->contains($friendshipRequest)) {
            $this->friendshipRequests->add($friendshipRequest);
            $friendshipRequest->setFriendshipRequester($this);
        }

        return $this;
    }

    public function removeFriendshipRequest(Friendship $friendshipRequest): self
    {
        if ($this->friendshipRequests->removeElement($friendshipRequest)) {
            // set the owning side to null (unless already changed)
            if ($friendshipRequest->getFriendshipRequester() === $this) {
                $friendshipRequest->setFriendshipRequester(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Friendship>
     */
    public function getFriendshipAccepters(): Collection
    {
        return $this->friendshipAccepters;
    }

    public function addFriendshipAccepter(Friendship $friendshipAccepter): self
    {
        if (!$this->friendshipAccepters->contains($friendshipAccepter)) {
            $this->friendshipAccepters->add($friendshipAccepter);
            $friendshipAccepter->setFriendshipAccepter($this);
        }

        return $this;
    }

    public function removeFriendshipAccepter(Friendship $friendshipAccepter): self
    {
        if ($this->friendshipAccepters->removeElement($friendshipAccepter)) {
            // set the owning side to null (unless already changed)
            if ($friendshipAccepter->getFriendshipAccepter() === $this) {
                $friendshipAccepter->setFriendshipAccepter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setPostedBy($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getPostedBy() === $this) {
                $item->setPostedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setPostedBy($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getPostedBy() === $this) {
                $review->setPostedBy(null);
            }
        }

        return $this;
    }

}
