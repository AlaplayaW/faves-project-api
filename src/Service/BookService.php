<?php
namespace App\Service;

use App\Repository\BookRepository;
use App\Entity\Review;
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

    // public function findBooksByFriends(array $friendIds)
    // {
    //     return $this->bookRepository->findByFriendIds($friendIds);
    // }

    // public function findBooksByCurrentUserFriends(int $userId)
    // {
    //     return $this->bookRepository->findByCurrentUserFriends($userId);
    // }

    // public function getBooksByUser(int $userId): array
    // {
    //   // Récupérer toutes les reviews postées par l'utilisateur avec l'ID $userId
    //   $reviews = $this->bookRepository->findBy(['user' => $userId]);
      
    //   return $reviews;
    // }

    // public function findBooksWithDetails()
    // {
    //     return $this->bookRepository->findBooksWithDetails();
    // }

    // public function findBooksWithDetailsByFriends(array $friendIds)
    // {
    //     return $this->bookRepository->findBooksWithDetailsByFriends($friendIds);
    // }

    // /**
    //  * @param User $user
    //  * @return Collection|Book[]
    //  */
    // public function getFriendsBooks(User $user): Collection | array
    // {

    //     /** @var Collection<User> $friends */
    //     $friends = array_merge(
    //         $user->getFriendshipRequests()->toArray(),
    //         $user->getFriendshipAccepters()->toArray()
    //     );

    //     // /** @var ArrayCollection<Book> $books */
    //     // $books = [];
    //     // /** @var User $friend */
    //     // foreach ($friends as $friend) {
    //     //     $friendBooks = $friend->getBooks();
    //     //     $books = array_merge($books, $friendBooks->toArray());
    //     // }

    //     // return $books->toArray();
    //     /** @var Collection<Book> $books */
    //     $books = [];
    //     /** @var User $friend */
    //     foreach ($friends as $friend) {
    //         $friendBooks = $friend->getBooks();
    //         foreach ($friendBooks as $book) {
    //             $books[] = $book;
    //         }
    //     }
    
    //     return $books;
    // }

    /**
     * @return Collection|Review[]
     */
    public function getBooksByNetwork(int $userId): Collection | array
    {
        // Code pour récupérer la liste des critiques de livres commentées par des amis de l'utilisateur connecté
      $booksReviews = $this->bookRepository->findBooksByNetwork($userId);

        return $booksReviews;
    }

}
