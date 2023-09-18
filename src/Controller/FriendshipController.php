<?php

namespace App\Controller;

use App\Service\FriendshipService;
use App\Service\BookService;
use App\Service\NetworkService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

#[AsController]
class FriendshipController extends AbstractController
{
    private UserService $userService;
    private FriendshipService $friendshipService;

    #[Required]
    public function __construct(UserService $userService, FriendshipService $friendshipService)
    {
        $this->userService = $userService;
        $this->friendshipService = $friendshipService;
    }
    // public function __construct(NetworkService $networkService, FriendshipService $friendshipService, BookService $bookService)
    // {
    //     $this->friendshipService = $friendshipService;
    //     $this->bookService = $bookService;
    //     $this->networkService = $networkService;
    // }

    // #[Route('/api/network/reviews', name: 'api_network_reviews', methods: ['GET'])]
    // public function getNetworkReviews(Request $request): JsonResponse
    // {
    //     // Récupérer l'utilisateur connecté à l'application
    //     $user = $this->networkService->getLoggedIndUser();
    //     if (!$user) {
    //         throw $this->createNotFoundException('User not found.');
    //     }

    //     // Récupérer l'ID de l'utilisateur connecté
    //     $userId = $user->getId();

    //     // Utiliser le service FriendshipService pour récupérer les reviews des friendships de l'utilisateur
    //     $reviews = $this->friendshipService->getNetworkReviews($userId);

    //     return $this->json($reviews);
    // }


    #[Route('/api/friends', name: 'get_friends', methods: ['GET'])]
    public function getFriends(): JsonResponse
    {
        // Récupérez l'ID de l'utilisateur actuellement connecté
        $user = $this->userService->getLoggedIndUser();
        $userId = $user->getId();

        $friends = $this->friendshipService->getValidatedFriends($userId);

        return $this->json($friends);
    }

    #[Route('/api/friend-requests', name: 'get_friend_requests', methods: ['GET'])]
    public function getFriendRequests(): JsonResponse
    {
        // Récupérez l'ID de l'utilisateur actuellement connecté
        $user = $this->userService->getLoggedIndUser();
        $userId = $user->getId();

        $friendRequests = $this->friendshipService->getFriendRequests($userId);


        return $this->json($friendRequests);
    }
}
