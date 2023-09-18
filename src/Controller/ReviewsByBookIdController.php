<?php 
namespace App\Controller;

use App\Service\BookService;
use App\Service\ReviewService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;


#[AsController]
class ReviewsByBookIdController extends AbstractController
{
    private BookService $bookService;
    private ReviewService $reviewService;
    private UserService $userService;

    public function __construct(BookService $bookService, UserService $userService, ReviewService $reviewService)
    {
        $this->bookService = $bookService;
        $this->userService = $userService;
        $this->reviewService = $reviewService;
    }

    public function __invoke(): JsonResponse
    {
        // Code pour récupérer la liste des livres commentés par les amis de l'utilisateur actuellement connecté
        return $this->json('');
    }
}
