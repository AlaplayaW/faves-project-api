<?php

namespace App\Service;

use App\Repository\ReviewRepository;
use App\Entity\Review;

class ReviewService
{
  private ReviewRepository $reviewRepository;

  public function __construct(ReviewRepository $reviewRepository)
  {
    $this->reviewRepository = $reviewRepository;
  }

  /**
   * @return array[]
   */
  public function getReviewsByUser(int $userId): array
  {
    $reviews = $this->reviewRepository->findBy(['user' => $userId]);

    // Transformer les reviews en un format adapté pour la réponse JSON
    $reviewsData = [];
    foreach ($reviews as $review) {
      $reviewsData[] = [
        'id' => $review->getId(),
        'rating' => $review->getRating(),
        'comment' => $review->getComment(),
        'item' => [
          'id' => $review->getBook()->getId(),
        ],
      ];
    }

    return $reviewsData;
  }


  /**
   * Récupère toutes les reviews postées par les amis du user connecté.
   *
   * @param int $userId
   * @return Review[]
   */
  public function getReviewsByNetwork(int $userId): array
  {
    // Récupérer toutes les reviews où l'utilisateur a posté un avis
    $reviews = $this->reviewRepository->findReviewsByNetwork($userId);

    return $reviews;
  }
}
