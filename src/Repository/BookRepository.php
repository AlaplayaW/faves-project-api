<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function save(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBooksByNetwork(int $userId): array
    {
        // Code pour récupérer la liste des livres postés par les amis de l'utilisateur connecté

        $qb = $this->createQueryBuilder('b')
            ->join('b.reviews', 'r')
            ->join('r.user', 'u')
            ->join('u.friendAccepters', 'f')
            ->where('f.status = :status')
            ->andWhere('(f.friendRequester = :user AND f.friendAccepter = u) OR (f.friendAccepter = :user AND f.friendRequester = u)')
            ->orderBy('r.createdAt', 'DESC')
            ->setParameter('status', 'accepted')
            ->setParameter('user', $userId);

        return $qb->getQuery()->getResult();
    }
    
    // public function findReviewsByUserFriends(int $userId)
    // {
    //     $connection = $this->getEntityManager()->getConnection();

    //     $sql = '
    //     SELECT b.title, r.comment
    //     FROM book b
    //     INNER JOIN review r ON b.id = r.book_id
    //     INNER JOIN "user" u ON r.user_id = u.id
    //     INNER JOIN friendship f ON (
    //         (f.status = :status AND f.friend_requester_id = :user AND f.friend_accepter_id = u.id)
    //         OR
    //         (f.status = :status AND f.friend_requester_id = u.id AND f.friend_accepter_id = :user)
    //     )
    //     ORDER BY r.created_at DESC;
    // ';

    //     $statement = $connection->prepare($sql);
    //     $statement->bindValue('user', $userId, \PDO::PARAM_INT); // \PDO::PARAM_INT pour valeurs entières
    //     $statement->bindValue('status', 'accepted');
    //     $res = $statement->executeQuery();
    //     return $res->fetchAllAssociative();
    // }

    // /**
    //  * @return Genre[] Returns an array of Genre objects for an Book ID
    //  */
    // public function findGenresByBook($bookId)
    // {
    //     return $this->createQueryBuilder('g')
    //         ->select('g')
    //         ->leftJoin('g.bookGenres', 'bookg')
    //         ->leftJoin('bookg.book', 'book')
    //         ->where('book.id = :bookId')
    //         ->setParameter('bookId', $bookId)
    //         ->getQuery()
    //         ->getResult();
    // }


    // public function findByFriendIds(array $friendIds)
    // {
    //     return $this->createQueryBuilder('book')
    //         ->select('book')
    //         ->join('book.user', 'user')
    //         ->where('user.id IN (:friendIds)')
    //         ->setParameter('friendIds', $friendIds)
    //         ->getQuery()
    //         ->getResult();
    // }

    // public function findByCurrentUserFriends(int $userId)
    // {
    //     return $this->createQueryBuilder('book')
    //         ->select('book')
    //         ->join('book.user', 'user')
    //         ->join('user.friends', 'friend')
    //         ->where('friend.id = :userId')
    //         ->setParameter('userId', $userId)
    //         ->getQuery()
    //         ->getResult();
    // }

    // public function findBooksAndReviewsByFriends(int $userId)
    // {
    //     return $this->createQueryBuilder('review')
    //         ->select('book.id AS bookId', 'book.title', 'review.rating', 'review.comment', 'review.createdAt')
    //         ->join('review.book', 'book')
    //         ->join('review.user', 'user')
    //         ->join('user.friendshipRequests', 'requester', 'WITH', 'requester.friendshipAccepter = :user AND requester.isAccepted = true')
    //         ->join('user.friendshipAccepters', 'accepter', 'WITH', 'accepter.friendshipRequester = :user AND accepter.isAccepted = true')
    //         ->where('requester.id IS NOT NULL OR accepter.id IS NOT NULL')
    //         ->setParameter('userId', $userId)
    //         ->orderBy('review.createdAt', 'DESC')
    //         ->getQuery()
    //         ->getResult();
    // }


    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
