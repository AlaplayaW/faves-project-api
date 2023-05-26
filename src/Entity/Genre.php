<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'genre', targetEntity: ItemGenre::class)]
    private Collection $itemGenres;

    public function __construct()
    {
        $this->itemGenres = new ArrayCollection();
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
            $itemGenre->setGenre($this);
        }

        return $this;
    }

    public function removeItemGenre(ItemGenre $itemGenre): self
    {
        if ($this->itemGenres->removeElement($itemGenre)) {
            // set the owning side to null (unless already changed)
            if ($itemGenre->getGenre() === $this) {
                $itemGenre->setGenre(null);
            }
        }

        return $this;
    }
}