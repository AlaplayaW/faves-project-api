<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\Timer;
use App\Repository\BookGenreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BookGenreRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['bookGenre:read']]
)]
class BookGenre
{
    use Timer;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[Groups(['book:read', 'review:read'])]
    #[ORM\ManyToOne(inversedBy: 'bookGenres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    // #[Groups(['genre:read'])]
    #[Groups(['bookGenre:read', 'booksByNetwork:read'])]
    // #[Groups(['review:read'])]
    #[ORM\ManyToOne(inversedBy: 'bookGenres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Genre $genre = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }
}
