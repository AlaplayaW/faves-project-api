<?php

namespace App\Service;

use App\Entity\Book;
use App\Repository\FriendshipRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Repository\BookRepository;

class FriendshipService
{
  private FriendshipRepository $friendshipRepository;
  private UserRepository $userRepository;
  private NetworkService $networkService;
  private BookRepository $bookRepository;


  public function __construct(NetworkService $networkService, FriendshipRepository $friendshipRepository, UserRepository $userRepository, BookRepository $bookRepository)
  {
    $this->friendshipRepository = $friendshipRepository;
    $this->userRepository = $userRepository;
    $this->networkService = $networkService;
    $this->bookRepository = $bookRepository;
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


  public function getValidatedFriends(int $userId): array
  {
      // Code pour récupérer la liste d'amis validés de l'utilisateur connecté
      $validatedFriends = $this->friendshipRepository->findValidatedFriends($userId);

      return $validatedFriends;
  }

  public function getFriendRequests(int $userId): array
  {
      // Code pour récupérer la liste des demandes d'amis en attente
      $friendRequests = $this->friendshipRepository->findFriendRequests($userId);

      return $friendRequests;
  }
  
}
