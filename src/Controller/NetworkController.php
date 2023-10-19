<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\FriendshipService;
use App\Service\BookService;
use App\Service\ReviewService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class NetworkController extends AbstractController
{

    private $reviewService;
    private BookService $bookService;
    private SerializerInterface $serializer;
    private FriendshipService $friendService;
    private Security $security;
    private UserRepository $userRepository;
    private ?User $user = null;


    public function __construct(
        ReviewService $reviewService,
        BookService $bookService,
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
            $this->user = $this->userRepository->findOneBy(['email' => $userFromToken->getUserIdentifier()]);
        }
    }

    // Recupère les livres postés par les amis
    #[Route('/fv1-api/network/books', name: 'get_books_by_network', methods: ['GET'])]
    public function getBooksByNetwork(): JsonResponse
    {
        $this->getCurrentUser();
        if ($this->user === null) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié'], Response::HTTP_UNAUTHORIZED);
        }

        $books = $this->bookService->getBooksByNetwork($this->user->getId());
        // converti $booksReviews en format JSON avec les groupes de sérialisation associés à 'book:read'.
        $jsonBookList = $this->serializer->serialize($books, 'json', ['groups' => ['book:read', 'booksByNetwork:read', 'time:read']]);
        return new JsonResponse($jsonBookList, Response::HTTP_OK, [], true);
    }

    // Recupère les avis des amis du user connecté
    #[Route('/fv1-api/network/reviews', methods: ['GET'], name: 'get_reviews_by_network')]
    public function getReviewsByNetwork(): JsonResponse
    {
        $this->getCurrentUser();
        if ($this->user === null) {
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
        $this->getCurrentUser();
        if ($this->user === null) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié'], Response::HTTP_UNAUTHORIZED);
        }

        $friends = $this->friendService->getFriendsByNetwork($this->user->getId());
        // converti $friendsReviews en format JSON avec les groupes de sérialisation associés à 'friend:read'.
        $jsonFriendList = $this->serializer->serialize($friends, 'json', ['groups' => ['friend:read', 'friendsByNetwork:read', 'time:read']]);
        return new JsonResponse($jsonFriendList, Response::HTTP_OK, [], true);
    }
}
