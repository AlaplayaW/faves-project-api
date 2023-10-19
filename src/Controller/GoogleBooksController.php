<?php

namespace App\Controller;

use App\Service\GoogleBooksService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class GoogleBooksController extends AbstractController
{
  private GoogleBooksService $googleBooksService;

  public function __construct(GoogleBooksService $googleBooksService)
  {
    $this->googleBooksService = $googleBooksService;
  }

  public function __invoke(Request $request): JsonResponse
  {
    $query = $request->query->get('title');
    $books = $this->googleBooksService->searchBooks($query);

    return new JsonResponse($books);
  }
}
