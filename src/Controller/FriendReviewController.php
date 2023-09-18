<?php
namespace App\Controller;

use App\Service\FriendReviewService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FriendReviewController extends AbstractController
{
    private FriendReviewService $friendReviewService;

    public function __construct(FriendReviewService $friendReviewService)
    {
        $this->friendReviewService = $friendReviewService;
    }


    #[Route('/api/friend-reviews/{userId}', name: 'friend_reviews', methods: ['GET'])]
    public function getFriendReviews(int $userId): JsonResponse
    {
        $friendReviews = $this->friendReviewService->getBooksAndReviewsByFriends($userId);

        return $this->json($friendReviews);
    }

}
