<?php

namespace App\Controller;

use App\Service\FriendshipService;
use App\Service\ItemService;
use App\Service\NetworkService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class FriendshipController extends AbstractController
{
    private FriendshipService $friendshipService;
    private NetworkService $networkService;
    private ItemService $itemService;


    public function __construct(NetworkService $networkService, FriendshipService $friendshipService, ItemService $itemService)
    {
        $this->friendshipService = $friendshipService;
        $this->itemService = $itemService;
        $this->networkService = $networkService;
    }

    #[Route('/api/network/reviews', name: 'api_network_reviews', methods: ['GET'])]
    public function getNetworkReviews(Request $request): JsonResponse
    {
        // Récupérer l'utilisateur connecté à l'application
        $user = $this->networkService->getLoggedIndUser();
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        // Récupérer l'ID de l'utilisateur connecté
        $userId = $user->getId();

        // Utiliser le service FriendshipService pour récupérer les reviews des friendships de l'utilisateur
        $reviews = $this->friendshipService->getNetworkReviews($userId);
  
        return $this->json($reviews);
    }

}
