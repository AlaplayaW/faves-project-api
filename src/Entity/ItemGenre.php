<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\Timer;
use App\Repository\ItemGenreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ItemGenreRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['itemGenre:read']]
)]
class ItemGenre
{
    use Timer;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[Groups(['item:read', 'review:read'])]
    #[ORM\ManyToOne(inversedBy: 'itemGenres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Item $item = null;

    // #[Groups(['genre:read'])]
    #[Groups(['genre:read', 'item:read', 'itemGenre:read'])]
    // #[Groups(['review:read'])]
    #[ORM\ManyToOne(inversedBy: 'itemGenres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Genre $genre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): self
    {
        $this->item = $item;

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
