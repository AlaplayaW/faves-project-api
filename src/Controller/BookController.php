<?php

namespace App\Controller;

use App\Service\BookService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

// #[AsController]
class BookController extends AbstractController
{

  private BookService $bookService;
  private UserService $userService;

  public function __construct(BookService $bookService, UserService $userService)
  {
    $this->bookService = $bookService;
    $this->userService = $userService;
  }

  // #[Route('/books/reviews-by-friends', methods: ['GET'], name: 'get_books_reviews')]
  public function getBooksReviews(): JsonResponse
  {
        // Récupérez l'ID de l'utilisateur actuellement connecté
        $user = $this->userService->getLoggedIndUser();
        $userId = $user->getId();

    // Code pour récupérer la liste des livres commentés par les amis de l'utilisateur actuellement connecté
    $booksReviews = $this->bookService->getBooksByNetwork($userId);

    return $this->json($booksReviews);
  }
}
