<?php

namespace App\Service;

use App\Entity\Book;
use App\Repository\FriendshipRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\Collection;

class FriendshipService
{
  private FriendshipRepository $friendshipRepository;
  private UserRepository $userRepository;
  private NetworkService $networkService;


  public function __construct(NetworkService $networkService, FriendshipRepository $friendshipRepository, UserRepository $userRepository)
  {
    $this->friendshipRepository = $friendshipRepository;
    $this->userRepository = $userRepository;
    $this->networkService = $networkService;
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

  // /**
  //  * @return User[]
  //  */
  // public function getFriendsByUser(int $userId): array
  // {
  //   $friends = $this->friendshipRepository->findFriendsByUserId($userId);
    
  //   return $friends;
  // }

  // public function getNetworkReviews(int $userId): array
  // {
  //   // $friends = $this->networkService->getFriends($user);
  //   $friends = $this->friendshipRepository->getFriendsByUser($userId);

  //   return $friends;
  // }


  // public function getValidatedFriends(int $userId): array
  // {
  //     // Code pour récupérer la liste d'amis validés de l'utilisateur connecté
  //     $validatedFriends = $this->friendshipRepository->findValidatedFriends($userId);

  //     return $validatedFriends;
  // }

  public function getFriendRequests(int $userId): array
  {
      // Code pour récupérer la liste des demandes d'amis en attente
      $friendRequests = $this->friendshipRepository->findFriendRequests($userId);

      return $friendRequests;
  }
  
}
