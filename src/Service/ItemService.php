<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Item;
use App\Repository\ItemRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class ItemService
{
    private $entityManager;
    private ItemRepository $itemRepository;

    public function __construct(EntityManagerInterface $entityManager, ItemRepository $itemRepository)
    {
        $this->entityManager = $entityManager;
        $this->itemRepository = $itemRepository;
    }

    public function findItemsWithDetailsByFriends(array $friendIds)
    {
        return $this->itemRepository->findByFriendIds($friendIds);
    }

    public function findItemsWithDetailsByCurrentUserFriends(int $userId)
    {
        return $this->itemRepository->findByCurrentUserFriends($userId);
    }

    public function getItemsByUser(int $userId): array
    {
      // Récupérer toutes les reviews postées par l'utilisateur avec l'ID $userId
      $reviews = $this->itemRepository->findBy(['postedBy' => $userId]);
      
      return $reviews;
    }

    // public function findItemsWithDetails()
    // {
    //     return $this->itemRepository->findItemsWithDetails();
    // }

    // public function findItemsWithDetailsByFriends(array $friendIds)
    // {
    //     return $this->itemRepository->findItemsWithDetailsByFriends($friendIds);
    // }

    /**
     * @param User $user
     * @return Collection|Item[]
     */
    public function getFriendsItems(User $user): Collection | array
    {

        /** @var Collection<User> $friends */
        $friends = array_merge(
            $user->getFriendshipRequests()->toArray(),
            $user->getFriendshipAccepters()->toArray()
        );

        // /** @var ArrayCollection<Item> $items */
        // $items = [];
        // /** @var User $friend */
        // foreach ($friends as $friend) {
        //     $friendItems = $friend->getItems();
        //     $items = array_merge($items, $friendItems->toArray());
        // }

        // return $items->toArray();
        /** @var Collection<Item> $items */
        $items = [];
        /** @var User $friend */
        foreach ($friends as $friend) {
            $friendItems = $friend->getItems();
            foreach ($friendItems as $item) {
                $items[] = $item;
            }
        }
    
        return $items;
    }
}
