<?php

namespace App\Service;

use App\Repository\BookRepository;
use App\Entity\Book;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class BookService
{
    private $entityManager;
    private BookRepository $bookRepository;

    public function __construct(EntityManagerInterface $entityManager, BookRepository $bookRepository)
    {
        $this->entityManager = $entityManager;
        $this->bookRepository = $bookRepository;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBooksByNetwork(int $userId): Collection | array
    {
        // Code pour récupérer la liste des critiques de livres commentées par des amis de l'utilisateur connecté
        $books = $this->bookRepository->findBooksByNetwork($userId);

        return $books;
    }
}
