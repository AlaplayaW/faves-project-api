<?php

namespace App\Service;

use App\Repository\FriendshipRepository;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;

class FriendshipService
{
  private FriendshipRepository $friendshipRepository;


  public function __construct(FriendshipRepository $friendshipRepository)
  {
    $this->friendshipRepository = $friendshipRepository;
  }

  /**
   * @return Collection|User[]
   */
  public function getFriendsByNetwork(int $userId): Collection | array
  {
    // Code pour récupérer la liste des critiques de livres commentées par des amis de l'utilisateur connecté
    $friends = $this->friendshipRepository->findFriendsByNetwork($userId);

    return $friends;
  }

  public function getFriendRequests(int $userId): array
  {
    $friendRequests = $this->friendshipRepository->findFriendRequests($userId);

    return $friendRequests;
  }
}
