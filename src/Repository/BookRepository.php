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
		$entityManager = $this->getEntityManager();

		$queryBuilder = $entityManager->createQuery("
			SELECT DISTINCT b
			FROM App\Entity\Book b
			JOIN App\Entity\User u WITH b.user = u
			LEFT JOIN App\Entity\Friendship f WITH (u = f.friendRequester OR u = f.friendAccepter) 
			AND f.status LIKE 'accepted'
			WHERE u.id = :userId OR f.friendRequester = :userId OR f.friendAccepter = :userId
			ORDER BY b.createdAt DESC
			")
			->setParameter('userId', $userId);

		return $queryBuilder->getResult();
	}


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

	public function findBooksAndReviewsByFriends(int $userId)
	{
		return $this->createQueryBuilder('review')
			->select('book.id AS bookId', 'book.title', 'review.rating', 'review.comment', 'review.createdAt')
			->join('review.book', 'book')
			->join('review.user', 'user')
			->join('user.friendRequesters', 'requester', 'WITH', 'requester.friendshipAccepter = :user AND requester.status = true')
			->join('user.friendAccepters', 'accepter', 'WITH', 'accepter.friendshipRequester = :user AND accepter.status = true')
			->where('requester.id IS NOT NULL OR accepter.id IS NOT NULL')
			->setParameter('userId', $userId)
			->orderBy('review.createdAt', 'DESC')
			->getQuery()
			->getResult();
	}
}
