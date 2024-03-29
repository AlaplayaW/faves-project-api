<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use App\Entity\Traits\Timer;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    // normalizationContext: ['groups' => ['user:read', 'friends', 'book:read']],
    normalizationContext: ['groups' => ['user:read']],
    // denormalizationContext: ['groups' => ['user:write', 'book:write']],
    operations: [
            new Get(),
            new GetCollection(),
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
    private ?int $id = null;

    #[Groups(['user:read', 'user:write'])]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[Groups(['user:read', 'user:write'])]
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[Groups(['user:write'])]
    #[ORM\Column]
    private ?string $password = null;

    #[Groups(['user:read', 'user:write', 'reviewsByNetwork:read'])]
    #[ORM\Column(length: 100)]
    private ?string $pseudo = null;

    #[Groups(['user:read', 'user:write'])]
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Media $media = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Book ::class)]
    private Collection $books;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Review::class, orphanRemoval: true)]
    private Collection $reviews;

    #[ORM\OneToMany(mappedBy: 'friendRequester', targetEntity: Friendship::class, orphanRemoval: true)]
    private Collection $friendRequesters;

    #[ORM\OneToMany(mappedBy: 'friendAccepter', targetEntity: Friendship::class, orphanRemoval: true)]
    private Collection $friendAccepters;


    public function __construct()
    {
        $this->friendRequesters = new ArrayCollection();
        $this->friendAccepters = new ArrayCollection();
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


    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

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
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setUser($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getUser() === $this) {
                $book->setUser(null);
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
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Friendship>
     */
    public function getFriendRequesters(): Collection
    {
        return $this->friendRequesters;
    }

    public function addFriendRequester(Friendship $friendRequester): self
    {
        if (!$this->friendRequesters->contains($friendRequester)) {
            $this->friendRequesters->add($friendRequester);
            $friendRequester->setFriendRequester($this);
        }

        return $this;
    }

    public function removeFriendRequester(Friendship $friendRequester): self
    {
        if ($this->friendRequesters->removeElement($friendRequester)) {
            // set the owning side to null (unless already changed)
            if ($friendRequester->getFriendRequester() === $this) {
                $friendRequester->setFriendRequester(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Friendship>
     */
    public function getFriendAccepter(): Collection
    {
        return $this->friendAccepters;
    }

    public function addFriendAccepter(Friendship $friendAccepter): self
    {
        if (!$this->friendAccepters->contains($friendAccepter)) {
            $this->friendAccepters->add($friendAccepter);
            $friendAccepter->setFriendAccepter($this);
        }

        return $this;
    }

    public function removeFriendAccepter(Friendship $friendAccepter): self
    {
        if ($this->friendAccepters->removeElement($friendAccepter)) {
            // set the owning side to null (unless already changed)
            if ($friendAccepter->getFriendAccepter() === $this) {
                $friendAccepter->setFriendAccepter(null);
            }
        }

        return $this;
    }

}

// Je veux récupérer tous les livres (et leurs reviews) qui ont été commentés par un de mes amis.
// Le but est d'afficher une liste de livres avec pour chaque livre, tous les commentaires affiches par mes amis.
// Si le meme livre est commenté par plusieurs amis, je veux que le livre n'apparaisse qu'une seule fois, et que tous les commentaires soient visibles quand on clique sur le livre.
// La liste des livres doit faire apparaitre en 1er le livre qui a été commenté le plus récemment par un ami.