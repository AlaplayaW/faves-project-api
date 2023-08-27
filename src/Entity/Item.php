<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\ItemController;
use App\Controller\NetworkController;
use App\Entity\Traits\Timer;
use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['item:read', 'user:read']],
    // denormalizationContext: ['groups' => ['item:write']],
    // outputFormats: ['json'],

    operations: [
        new Get(),
        new GetCollection(),
        new GetCollection(
            name: 'get_items_posted_by_user',
            uriTemplate: '/network/items',
            controller: NetworkController::class,
        ),
        new Post(
            // outputFormats: ['json']
            // uriTemplate: '/items', 
            // status: 301,
            // controller: ItemController::class
        )
    ]
)]

#[ApiFilter(SearchFilter::class, properties: ['mediaType', 'title' => 'ipartial'],)]
class Item
{
    use Timer;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read', 'item:write', 'test'])]
    private ?int $id = null;

    #[Groups(['item:read', 'item:write'])]
    #[ORM\Column(length: 255)]
    private ?string $mediaType = null;

    #[Groups(['item:read', 'review:read', 'item:write'])]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Groups(['item:read', 'item:write'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subtitle = null;

    // #[Groups(['item:read', 'item:write'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    // #[Groups(['item:read', 'item:write'])]
    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['item:read', 'item:write'])]
    private ?User $postedBy = null;

    // #[Groups(['item:read', 'item:write'])]
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    // #[Groups(['item:read', 'item:write'])]
    private ?Media $media = null;

    // #[Groups(['item:read', 'item:write'])]
    #[ORM\OneToOne(mappedBy: 'item', cascade: ['persist', 'remove'])]
    private ?Book $book = null;

    // #[Groups(['item:read', 'item:write'])]
    #[ORM\OneToOne(mappedBy: 'item', cascade: ['persist', 'remove'])]
    private ?Movie $movie = null;

    #[Groups(['item:read'])]
    #[ORM\OneToMany(mappedBy: 'item', targetEntity: Review::class, orphanRemoval: true)]
    private Collection $reviews;

    #[Groups(['item:read'])]
    #[ORM\OneToMany(mappedBy: 'item', targetEntity: ItemGenre::class, orphanRemoval: true)]
    private Collection $itemGenres;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->itemGenres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMediaType(): ?string
    {
        return $this->mediaType;
    }

    public function setMediaType(string $mediaType): self
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPostedBy(): ?User
    {
        return $this->postedBy;
    }

    public function setPostedBy(?User $postedBy): self
    {
        $this->postedBy = $postedBy;

        return $this;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(Book $book): self
    {
        // set the owning side of the relation if necessary
        if ($book->getItem() !== $this) {
            $book->setItem($this);
        }

        $this->book = $book;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(Movie $movie): self
    {
        // set the owning side of the relation if necessary
        if ($movie->getItem() !== $this) {
            $movie->setItem($this);
        }

        $this->movie = $movie;

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
            $review->setItem($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getItem() === $this) {
                $review->setItem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ItemGenre>
     */
    public function getItemGenres(): Collection
    {
        return $this->itemGenres;
    }

    public function addItemGenre(ItemGenre $itemGenre): self
    {
        if (!$this->itemGenres->contains($itemGenre)) {
            $this->itemGenres->add($itemGenre);
            $itemGenre->setItem($this);
        }

        return $this;
    }

    public function removeItemGenre(ItemGenre $itemGenre): self
    {
        if ($this->itemGenres->removeElement($itemGenre)) {
            // set the owning side to null (unless already changed)
            if ($itemGenre->getItem() === $this) {
                $itemGenre->setItem(null);
            }
        }

        return $this;
    }


    /**
     * Get the average rating for the item.
     *
     * @return float|null
     *
     * @Groups({"item:read"})
     */
    // #[Groups(['item:read'])]
    public function getAverageRating(): ?float
    {
        return $this->calculateAverageRating();
    }


    /**
     * Calculate the average rating for the item.
     * 
     * calculer le averageRating d'un item :
     *   - on veut afficher la liste de tous les items postés par un user et ses amis.
     *   - Tous ces items possèdent des reviews qui ont une note (rating dans la table reviews).
     *   - chaque user qui poste un item poste en meme temps un review avec un rating.
     *   - chaque user ami avec la personne qui a posté un item peut aussi mettre un rating sur *     l'item.
     *   - on veut calculer l'averageRating qui se trouve dans la table Item, qui correspond à *     la moyenne des ratings données par son réseau d'amis. c'est à dire par les le user *     actuel  et par ses amis (friendshipAccepter et friendshipRequester)
     *   - Comment calculer cette note dans le back (symfony, doctrine, apiplatform)
     *
     * @return float|null
     */
    public function calculateAverageRating(): ?float
    {
        $totalRating = 0;
        $reviewCount = count($this->reviews);

        if ($reviewCount === 0) {
            return null;
        }

        foreach ($this->reviews as $review) {
            $totalRating += $review->getRating();
        }

        return $totalRating / $reviewCount;
    }
}


        // new GetCollection(
        //     name: 'get_friends_items',
        //     uriTemplate: '/items/friends',
        //     controller: ItemController::class,
        //     openapiContext: [
        //         'summary' => 'Get all items posted by friends of a user',
        //         'description' => '# Get all items posted by friends of a user',
        //     ]
        // ),