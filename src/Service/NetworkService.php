<?php
namespace App\Service;

use App\Entity\User;
use App\Repository\ItemGenreRepository;
use App\Repository\UserRepository;

class NetworkService
{
    private ItemGenreRepository $itemRepository;
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository, ItemGenreRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
        $this->userRepository = $userRepository;
    }

    public function findItemsWithDetailsByCurrentUserFriends(int $userId)
    {
        return $this->itemRepository->findItemsWithDetailsByCurrentUserFriends($userId);
    }
    
    public function getLoggedIndUser(): User
    {
        $user = $this->userRepository->findOneBy(['id' => 5]);

        return $user;
    }

}
