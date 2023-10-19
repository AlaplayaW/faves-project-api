<?php

namespace App\Controller;

use App\Service\FriendshipService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

#[AsController]
class FriendshipController extends AbstractController
{
    private UserService $userService;
    private FriendshipService $friendshipService;


    #[Required]
    public function __construct(
        UserService $userService,
        FriendshipService $friendshipService,
    ) {
        $this->userService = $userService;
        $this->friendshipService = $friendshipService;
    }

    #[Route('/fv1-api/friends', name: 'get_friends', methods: ['GET'])]
    public function getFriends(): JsonResponse
    {
        $user = $this->userService->getLoggedIndUser();
        $userId = $user->getId();

        $friends = $this->friendshipService->getFriendsByNetwork($userId);

        return $this->json($friends);
    }

    #[Route('/fv1-api/friend-requests', name: 'get_friend_requests', methods: ['GET'])]
    public function getFriendRequests(): JsonResponse
    {
        $user = $this->userService->getLoggedIndUser();
        $userId = $user->getId();

        $friendRequests = $this->friendshipService->getFriendRequests($userId);


        return $this->json($friendRequests);
    }
}
