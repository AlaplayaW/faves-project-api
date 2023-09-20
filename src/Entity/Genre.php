<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\Timer;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => 'genre:read'],
    denormalizationContext: ['groups' => 'genre:write'],
    )]
    class Genre
{
    use Timer;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['genre:read', 'bookGenre:read', 'booksByNetwork:read'])]
    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'genre', targetEntity: BookGenre::class, orphanRemoval: true)]
    private Collection $bookGenres;

    public function __construct()
    {
        $this->bookGenres = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, BookGenre>
     */
    public function getBookGenres(): Collection
    {
        return $this->bookGenres;
    }

    public function addBookGenre(BookGenre $bookGenre): self
    {
        if (!$this->bookGenres->contains($bookGenre)) {
            $this->bookGenres->add($bookGenre);
            $bookGenre->setGenre($this);
        }

        return $this;
    }

    public function removeBookGenre(BookGenre $bookGenre): self
    {
        if ($this->bookGenres->removeElement($bookGenre)) {
            // set the owning side to null (unless already changed)
            if ($bookGenre->getGenre() === $this) {
                $bookGenre->setGenre(null);
            }
        }

        return $this;
    }

}
