<?php 
namespace App\Controller;

use App\Service\BookService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class BooksAndReviewsByFriendsController extends AbstractController
{
    private BookService $bookService;
    private UserService $userService;
    private SerializerInterface $serializer;

    public function __construct(BookService $bookService, UserService $userService, SerializerInterface $serializer)
    {
        $this->bookService = $bookService;
        $this->userService = $userService;
        $this->serializer = $serializer;
    }

    public function __invoke(): JsonResponse
    {
        // Récupérez l'ID de l'utilisateur actuellement connecté
        $user = $this->userService->getLoggedIndUser();
        $userId = $user->getId();

        // Code pour récupérer la liste des livres commentés par les amis de l'utilisateur actuellement connecté
        $booksReviews = $this->bookService->getBooksByNetwork($userId);
        // converti $booksReviews en format JSON avec les groupes de sérialisation associés à 'book:read'.
        $jsonBookList = $this->serializer->serialize($booksReviews, 'json', ['groups' => 'test:read']);
        return new JsonResponse($jsonBookList, HttpFoundationResponse::HTTP_OK, [], true);
    }
}
