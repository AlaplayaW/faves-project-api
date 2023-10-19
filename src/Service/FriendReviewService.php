<?php

namespace App\Service;

use App\Repository\ReviewRepository;

class FriendReviewService
{
    private ReviewRepository $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function getBooksAndReviewsByFriends(int $userId)
    {
        return $this->reviewRepository->findBooksAndReviewsByFriends($userId);
    }
}
