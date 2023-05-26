<?php

namespace App\Entity;

use App\Repository\FriendshipRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FriendshipRepository::class)]
class Friendship
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'friendshipRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $friendshipRequester = null;

    #[ORM\ManyToOne(inversedBy: 'friendshipAccepters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $friendshipAccepter = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFriendshipRequester(): ?User
    {
        return $this->friendshipRequester;
    }

    public function setFriendshipRequester(?User $friendshipRequester): self
    {
        $this->friendshipRequester = $friendshipRequester;

        return $this;
    }

    public function getFriendshipAccepter(): ?User
    {
        return $this->friendshipAccepter;
    }

    public function setFriendshipAccepter(?User $friendshipAccepter): self
    {
        $this->friendshipAccepter = $friendshipAccepter;

        return $this;
    }
}
