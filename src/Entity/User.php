<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\Timer;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`user`')]
#[ApiResource]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use Timer;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $firstname = null;

    #[ORM\Column(length: 100)]
    private ?string $lastname = null;

    #[ORM\Column(length: 100)]
    private ?string $username = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $phone = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Media $userMedia = null;

    #[ORM\OneToMany(mappedBy: 'friendshipRequester', targetEntity: Friendship::class, orphanRemoval: true)]
    private Collection $friendshipRequests;

    #[ORM\OneToMany(mappedBy: 'friendshipAccepter', targetEntity: Friendship::class, orphanRemoval: true)]
    private Collection $friendshipAccepters;

    #[ORM\OneToMany(mappedBy: 'postedBy', targetEntity: Item::class)]
    private Collection $items;

    #[ORM\OneToMany(mappedBy: 'postedBy', targetEntity: Review::class, orphanRemoval: true)]
    private Collection $reviews;


    public function __construct()
    {
        $this->friendshipRequests = new ArrayCollection();
        $this->friendshipAccepters = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getUserMedia(): ?Media
    {
        return $this->userMedia;
    }

    public function setUserMedia(?Media $userMedia): self
    {
        $this->userMedia = $userMedia;

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
