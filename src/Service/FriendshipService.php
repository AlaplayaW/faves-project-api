<?php

namespace App\Service;

use App\Repository\FriendshipRepository;
use App\Repository\UserRepository;
use App\Entity\User;

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
   * @return User[]
   */
  public function getFriendsByUser(int $userId): array
  {
    $friends = $this->friendshipRepository->findFriendsByUserId($userId);
    
    return $friends;
  }

  public function getNetworkReviews(int $userId): array
  {
    // $friends = $this->networkService->getFriends($user);
    $friends = $this->friendshipRepository->getFriendsByUser($userId);

    return $friends;
  }

  
}
