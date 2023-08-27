<?php
namespace App\Controller;

use App\Service\FriendshipService;
use App\Service\ItemService;
use App\Service\NetworkService;
use App\Service\ReviewService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class NetworkController extends AbstractController
{

    private $friendshipService;
    private $reviewService;
    private $itemService;
    private $networkService;

    public function __construct(
        FriendshipService $friendshipService,
        ReviewService $reviewService,
        ItemService $itemService,
        NetworkService $networkService
    ) {
        $this->friendshipService = $friendshipService;
        $this->reviewService = $reviewService;
        $this->itemService = $itemService;
        $this->networkService = $networkService;
    }

    // Recupère les amis du user connecté
    #[Route('/api/network/friends', methods: ['GET'], name: 'get_friends_by_user')]
    public function getFriends(): JsonResponse
    {

        $user = $this->getLoggedInUser();
        $userId = $user->getId();

        $friends = $this->friendshipService->getFriendsByUser($userId);

        return $this->json($friends);
    }
    // Recupère les avis des amis du user connecté
    #[Route('/api/network/friends/reviews', methods: ['GET'], name: 'get_reviews_by_user_friends')]
    public function getFriendsReviewsByUser(): JsonResponse
    {
        $user = $this->getLoggedInUser();
        $userId = $user->getId();
    
        $reviews = $this->reviewService->findReviewsByUserFriends($userId);
    
        return $this->json($reviews);
    }

    // Recupère les avis du user connecté
    #[Route('/api/network/reviews', methods: ['GET'], name: 'get_reviews_by_user')]
    public function getReviews(): JsonResponse
    {
        $user = $this->getLoggedInUser();
        $userId = $user->getId();

        $reviews = $this->reviewService->getReviewsByUser($userId);

        return $this->json($reviews);
    }


    // Recupère les livres et films postés par le user connecté
    #[Route('/api/network/items', methods: ['GET'], name: 'get_items_posted_by_user')]
    public function getItemsPosted(): JsonResponse
    {
        $user = $this->getLoggedInUser();
        $userId = $user->getId();
        $items = $this->itemService->getItemsByUser($userId);

        return $this->json($items);
    }

    // Recupère le user connecté
    private function getLoggedInUser()
    {
        $user = $this->networkService->getLoggedIndUser();

        return $user;
    }

}
