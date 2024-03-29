<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\FriendshipService;
use App\Service\BookService;
use App\Service\ReviewService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Service\Attribute\Required;

#[AsController]
class NetworkController extends AbstractController
{

    private $friendshipService;
    private $reviewService;
    private BookService $bookService;
    private UserService $userService;
    private SerializerInterface $serializer;

    private User $user;

    #[Required]
    public function __construct(
        ReviewService $reviewService,
        BookService $bookService,
        UserService $userService,
        SerializerInterface $serializer
    ) {
        $this->reviewService = $reviewService;
        $this->bookService = $bookService;
        $this->userService = $userService;
        $this->serializer = $serializer;
        $this->user = $userService->getLoggedIndUser();
    }

    // Recupère les livres postés par les amis
    #[Route('/api/network/books', name: 'get_books_by_network', methods: ['GET'])]
    public function getBooksByNetwork(): JsonResponse
    {
        $books = $this->bookService->getBooksByNetwork($this->user->getId());

        // converti $booksReviews en format JSON avec les groupes de sérialisation associés à 'book:read'.
        $jsonBookList = $this->serializer->serialize($books, 'json', ['groups' => 'booksByNetwork:read']);
        return new JsonResponse($jsonBookList, Response::HTTP_OK, [], true);
    }

    // Recupère les avis des amis du user connecté
    #[Route('/api/network/reviews', methods: ['GET'], name: 'get_reviews_by_network')]
    public function getReviewsByNetwork(): JsonResponse
    {
        $reviews = $this->reviewService->getReviewsByNetwork($this->user->getId());

        $jsonReviewList = $this->serializer->serialize($reviews, 'json', ['groups' => ['review:read', 'reviewsByNetwork:read', 'time:read']]);

        return new JsonResponse($jsonReviewList, Response::HTTP_OK, [], true);
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
