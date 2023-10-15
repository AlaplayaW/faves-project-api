<?php
namespace App\Controller;

use App\Entity\User;
use App\Service\FriendshipService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class RecommendationController extends AbstractController
{
    public function __construct(
        private FriendshipService $friendshipService,
        private EntityManagerInterface $entityManager
    ) {}


    // #[Route('/fv1-api/users/{userId}/friends', methods: ['GET'], name: 'get_user_friends')]

    // public function getUserFriends(User $user): JsonResponse
    // {
    //     $friends = $this->friendshipService->findFriendsOfUser($user);

    //     return $this->json($friends);
    // }

}
