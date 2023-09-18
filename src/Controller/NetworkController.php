<?php
namespace App\Controller;

use App\Service\FriendshipService;
use App\Service\BookService;
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
    private $bookService;
    private $networkService;

    public function __construct(
        FriendshipService $friendshipService,
        ReviewService $reviewService,
        BookService $bookService,
        NetworkService $networkService
    ) {
        $this->friendshipService = $friendshipService;
        $this->reviewService = $reviewService;
        $this->bookService = $bookService;
        $this->networkService = $networkService;
    }

    // Recupère les amis du user connecté
    // #[Route('/api/network/friends', methods: ['GET'], name: 'get_user_friends')]
    // public function getFriends(): JsonResponse
    // {

    //     $user = $this->getLoggedInUser();
    //     $userId = $user->getId();

    //     $friends = $this->friendshipService->getFriendsByUser($userId);

    //     return $this->json($friends);
    // }

    // // Recupère les avis des amis du user connecté
    // #[Route('/api/network/friends/books-reviews', methods: ['GET'], name: 'get_friends_books_reviews')]
    // public function getFriendsReviewsByUser(): JsonResponse
    // {
    //     $user = $this->getLoggedInUser();
    //     $userId = $user->getId();
    
    //     $reviews = $this->reviewService->findReviewsByUserFriends($userId);
    
    //     return $this->json($reviews);
    // }

    // // Recupère les avis du user connecté
    // #[Route('/api/network/user/books-reviews', methods: ['GET'], name: 'get_user_books_reviews')]
    // public function getReviews(): JsonResponse
    // {
    //     $user = $this->getLoggedInUser();
    //     $userId = $user->getId();

    //     $reviews = $this->reviewService->getReviewsByUser($userId);

    //     return $this->json($reviews);
    // }


    // // Recupère les livres et films postés par le user connecté
    // #[Route('/api/network/user/books', methods: ['GET'], name: 'get_user_books')]
    // public function getBooksPosted(): JsonResponse
    // {
    //     $user = $this->getLoggedInUser();
    //     $userId = $user->getId();
    //     $books = $this->bookService->getBooksByUser($userId);

    //     return $this->json($books);
    // }

    // Recupère le user connecté
    // private function getLoggedInUser()
    // {
    //     $user = $this->networkService->getLoggedIndUser();

    //     return $user;
    // }

}
