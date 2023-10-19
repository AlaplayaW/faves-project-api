<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\Timer;
use App\Repository\BookRepository;
use App\Controller\GoogleBooksController;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\NetworkController;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
	normalizationContext: ['groups' => ['book:read', 'time:read']],
	denormalizationContext: ['groups' => ['book:write']],
	security: 'is_granted("ROLE_USER")',

	operations: [
		new Get(),
		new GetCollection(
			normalizationContext: ['groups' => ['book:read', 'booksByNetwork:read']],
			denormalizationContext: ['groups' => ['book:write']],
			name: 'get_books_by_network',
			uriTemplate: '/network/books',
			controller: NetworkController::class,
			openapiContext: [
				'summary' => "Récupère la liste des livres commentés par les amis de l'utilisateur actuellement connecté",
			],
		),
		new GetCollection(
			uriTemplate: '/google-books/search',
			controller: GoogleBooksController::class,
			openapiContext: ['security' => [['JWT' => []]]],
		),
		new Post(),
	]
)]

class Book
{
	use Timer;

	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	#[Groups(['book:read'])]
	private ?int $id = null;

	#[Groups(['book:read', 'book:write', 'reviewsByNetwork:read'])]
	#[ORM\Column(length: 255, nullable: true)]
	#[ApiFilter(SearchFilter::class, strategy: 'partial')]
	private ?string $title = null;

	#[Groups(['book:read', 'book:write'])]
	#[ORM\Column(length: 255, nullable: true)]
	private ?string $subtitle = null;

	#[Groups(['book:read', 'book:write'])]
	#[ORM\Column(type: Types::TEXT, nullable: true)]
	private ?string $description = null;

	#[Groups(['book:read', 'book:write'])]
	#[ORM\Column(length: 255, nullable: true)]
	private ?string $printType = null;

	#[Groups(['book:read', 'book:write'])]
	#[ORM\Column(length: 255, nullable: true)]
	private ?string $publisher = null;

	#[Groups(['book:read', 'book:write'])]
	#[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
	private array $authors = [];

	#[Groups(['book:read', 'book:write'])]
	#[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
	private ?DateTimeInterface $publishedDate = null;

	#[Groups(['book:read', 'book:write'])]
	#[ORM\Column(nullable: true)]
	private ?int $pageCount = null;

	#[ORM\ManyToOne(inversedBy: 'books')]
	#[ORM\JoinColumn(nullable: false)]
	#[Groups(['book:read', 'book:write'])]
	private ?User $user = null;

	#[ORM\OneToOne(cascade: ['persist', 'remove'])]
	#[Groups(['book:read', 'book:write'])]
	private ?Media $media = null;

	#[Groups(['book:read', 'review:write'])]
	#[ORM\OneToMany(mappedBy: 'book', targetEntity: Review::class, orphanRemoval: true)]
	private Collection $reviews;

	#[Groups(['book:read'])]
	#[ORM\OneToMany(mappedBy: 'book', targetEntity: BookGenre::class, orphanRemoval: true)]
	private Collection $bookGenres;


	/**
	 * @return 
	 */
	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @param  $id 
	 * @return self
	 */
	public function setId(?int $id): self
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * Get the value of title
	 *
	 * @return ?string
	 */
	public function getTitle(): ?string
	{
		return $this->title;
	}

	/**
	 * Set the value of title
	 *
	 * @param ?string $title
	 *
	 * @return self
	 */
	public function setTitle(string $title): self
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * @return 
	 */
	public function getSubtitle(): ?string
	{
		return $this->subtitle;
	}

	/**
	 * @param  $subtitle 
	 * @return self
	 */
	public function setSubtitle(?string $subtitle): self
	{
		$this->subtitle = $subtitle;
		return $this;
	}

	/**
	 * @return 
	 */
	public function getDescription(): ?string
	{
		return $this->description;
	}

	/**
	 * @param  $description 
	 * @return self
	 */
	public function setDescription(?string $description): self
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return 
	 */
	public function getPrintType(): ?string
	{
		return $this->printType;
	}

	/**
	 * @param  $printType 
	 * @return self
	 */
	public function setPrintType(?string $printType): self
	{
		$this->printType = $printType;
		return $this;
	}


	/**
	 * @return 
	 */
	public function getPublisher(): ?string
	{
		return $this->publisher;
	}

	/**
	 * @param  $publisher 
	 * @return self
	 */
	public function setPublisher(?string $publisher): self
	{
		$this->publisher = $publisher;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getAuthors(): array
	{
		return $this->authors;
	}

	/**
	 * @param array $authors 
	 * @return self
	 */
	public function setAuthors(array $authors): self
	{
		$this->authors = $authors;
		return $this;
	}

	/**
	 * @return 
	 */
	public function getPublishedDate(): ?DateTimeInterface
	{
		return $this->publishedDate;
	}

	/**
	 * @param  $publishedDate 
	 * @return self
	 */
	public function setPublishedDate(?DateTimeInterface $publishedDate): self
	{
		$this->publishedDate = $publishedDate;
		return $this;
	}

	/**
	 * @return 
	 */
	public function getPageCount(): ?int
	{
		return $this->pageCount;
	}

	/**
	 * @param  $pageCount 
	 * @return self
	 */
	public function setPageCount(?int $pageCount): self
	{
		$this->pageCount = $pageCount;
		return $this;
	}

	/**
	 * @return 
	 */
	public function getUser(): ?User
	{
		return $this->user;
	}

	/**
	 * @param  $user 
	 * @return self
	 */
	public function setUser(?User $user): self
	{
		$this->user = $user;
		return $this;
	}

	/**
	 * @return 
	 */
	public function getMedia(): ?Media
	{
		return $this->media;
	}

	/**
	 * @param  $media 
	 * @return self
	 */
	public function setMedia(?Media $media): self
	{
		$this->media = $media;
		return $this;
	}

	/**
	 * @return Collection
	 */
	public function getReviews(): Collection
	{
		return $this->reviews;
	}

	/**
	 * @param Collection $reviews 
	 * @return self
	 */
	public function setReviews(Collection $reviews): self
	{
		$this->reviews = $reviews;
		return $this;
	}

	/**
	 * @return Collection
	 */
	public function getBookGenres(): Collection
	{
		return $this->bookGenres;
	}

	/**
	 * @param Collection $bookGenres 
	 * @return self
	 */
	public function setBookGenres(Collection $bookGenres): self
	{
		$this->bookGenres = $bookGenres;
		return $this;
	}


	/**
	 * Get the average rating for the item.
	 *
	 * @return float|null
	 *
	 * @Groups({"item:read"})
	 */
	#[Groups(['book:read'])]
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
