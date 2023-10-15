<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\FriendshipService;
use App\Service\BookService;
use App\Service\ReviewService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Service\Attribute\Required;

#[AsController]
class NetworkController extends AbstractController
{

    private $reviewService;
    private BookService $bookService;
    private SerializerInterface $serializer;
    private FriendshipService $friendService;
    private Security $security;
    private UserRepository $userRepository;
    // private User $user;
    private ?User $user = null; // Initialisé à null pour éviter des erreurs si non initialisé


    public function __construct(
        ReviewService $reviewService,
        BookService $bookService,
        UserService $userService,
        SerializerInterface $serializer,
        Security $security,
        FriendshipService $friendService,
        UserRepository $userRepository
    ) {
        $this->reviewService = $reviewService;
        $this->bookService = $bookService;
        $this->serializer = $serializer;
        $this->friendService = $friendService;
        $this->security = $security;
        $this->userRepository = $userRepository;
    }

    private function getCurrentUser(): void
    {
        $userFromToken = $this->security->getUser();
        if ($userFromToken instanceof UserInterface) {
            // Assurez-vous que l'utilisateur est connecté avant de l'initialiser
            $this->user = $this->userRepository->findOneBy(['email' => $userFromToken->getUserIdentifier()]);
        }
    }

    // Recupère les livres postés par les amis
    #[Route('/fv1-api/network/books', name: 'get_books_by_network', methods: ['GET'])]
    public function getBooksByNetwork(): JsonResponse
    {
        $this->getCurrentUser(); // Initialiser $this->user avec l'utilisateur actuel
        if ($this->user === null) {
            // Gérer le cas où l'utilisateur n'est pas authentifié
            return new JsonResponse(['message' => 'Utilisateur non authentifié'], Response::HTTP_UNAUTHORIZED);
        }

        //Récupérer les informations du user à partir de son username
        // $userFromToken = $this->security->getUser();
        // dd($this->getUser());

        $books = $this->bookService->getBooksByNetwork($this->user->getId());
        // converti $booksReviews en format JSON avec les groupes de sérialisation associés à 'book:read'.
        $jsonBookList = $this->serializer->serialize($books, 'json', ['groups' => ['book:read', 'booksByNetwork:read', 'time:read']]);
        return new JsonResponse($jsonBookList, Response::HTTP_OK, [], true);
    }

    // Recupère les avis des amis du user connecté
    #[Route('/fv1-api/network/reviews', methods: ['GET'], name: 'get_reviews_by_network')]
    public function getReviewsByNetwork(): JsonResponse
    {
        $this->getCurrentUser(); // Initialiser $this->user avec l'utilisateur actuel
        if ($this->user === null) {
            // Gérer le cas où l'utilisateur n'est pas authentifié
            return new JsonResponse(['message' => 'Utilisateur non authentifié'], Response::HTTP_UNAUTHORIZED);
        }

        $reviews = $this->reviewService->getReviewsByNetwork($this->user->getId());

        $jsonReviewList = $this->serializer->serialize($reviews, 'json', ['groups' => ['review:read', 'reviewsByNetwork:read', 'time:read']]);

        return new JsonResponse($jsonReviewList, Response::HTTP_OK, [], true);
    }

    // Recupère les amis du user connecté
    #[Route('/fv1-api/network/friends', methods: ['GET'], name: 'get_friends_by_network')]
    public function getFriendsByNetwork(): JsonResponse
    {
        $this->getCurrentUser(); // Initialiser $this->user avec l'utilisateur actuel
        if ($this->user === null) {
            // Gérer le cas où l'utilisateur n'est pas authentifié
            return new JsonResponse(['message' => 'Utilisateur non authentifié'], Response::HTTP_UNAUTHORIZED);
        }

        $friends = $this->friendService->getFriendsByNetwork($this->user->getId());
        // converti $friendsReviews en format JSON avec les groupes de sérialisation associés à 'friend:read'.
        $jsonFriendList = $this->serializer->serialize($friends, 'json', ['groups' => ['friend:read', 'friendsByNetwork:read', 'time:read']]);
        return new JsonResponse($jsonFriendList, Response::HTTP_OK, [], true);

        // $userId = $user->getId();

        // $friends = $this->friendshipService->getFriendsByUser($userId);

        // return $this->json($friends);
    }

    // // Recupère les avis des amis du user connecté
    // #[Route('/fv1-api/network/friends/books-reviews', methods: ['GET'], name: 'get_friends_books_reviews')]
    // public function getFriendsReviewsByUser(): JsonResponse
    // {
    //     $user = $this->getLoggedInUser();
    //     $userId = $user->getId();

    //     $reviews = $this->reviewService->findReviewsByUserFriends($userId);

    //     return $this->json($reviews);
    // }

    // // Recupère les avis du user connecté
    // #[Route('/fv1-api/network/user/books-reviews', methods: ['GET'], name: 'get_user_books_reviews')]
    // public function getReviews(): JsonResponse
    // {
    //     $user = $this->getLoggedInUser();
    //     $userId = $user->getId();

    //     $reviews = $this->reviewService->getReviewsByUser($userId);

    //     return $this->json($reviews);
    // }


    // // Recupère les livres et films postés par le user connecté
    // #[Route('/fv1-api/network/user/books', methods: ['GET'], name: 'get_user_books')]
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
