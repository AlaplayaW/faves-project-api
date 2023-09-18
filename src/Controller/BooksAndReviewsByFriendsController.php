<?php 
namespace App\Controller;

use App\Service\BookService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;


#[AsController]
class BooksAndReviewsByFriendsController extends AbstractController
{
    private BookService $bookService;
    private UserService $userService;

    public function __construct(BookService $bookService, UserService $userService)
    {
        $this->bookService = $bookService;
        $this->userService = $userService;
    }

    public function __invoke(): JsonResponse
    {
        // Récupérez l'ID de l'utilisateur actuellement connecté
        $user = $this->userService->getLoggedIndUser();
        $userId = $user->getId();

        // Code pour récupérer la liste des livres commentés par les amis de l'utilisateur actuellement connecté
        $booksReviews = $this->bookService->getBooksReviewsByFriends($userId);

        return $this->json($booksReviews);
    }
}
