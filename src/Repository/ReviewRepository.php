<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 *
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function save(Review $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Review $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //     public function findBooksAndReviewsByFriends(int $userId)
    //     {
    //         return $this->createQueryBuilder('review')
    //             ->select('book.id AS bookId', 'book.title', 'review.rating', 'review.comment', 'review.createdAt')
    //             ->join('review.book', 'book')
    //             ->join('review.user', 'user')
    //             ->join('user.friendshipRequests', 'requester', 'WITH', 'requester.friendshipAccepter = :user AND requester.isAccepted = true')
    //             ->join('user.friendshipAccepters', 'accepter', 'WITH', 'accepter.friendshipRequester = :user AND accepter.isAccepted = true')
    //             ->where('requester.id IS NOT NULL OR accepter.id IS NOT NULL')
    //             ->setParameter('userId', $userId)
    //             ->orderBy('review.createdAt', 'DESC')
    //             ->getQuery()
    //             ->getResult();
    //     }

    public function findReviewsByNetwork(int $userId): array
    {
        $qb = $this->createQueryBuilder('r')
            ->join('r.user', 'u')
            ->join('u.friendAccepters', 'f')
            ->where('f.status = :status')
            ->andWhere('(f.friendRequester = :user AND f.friendAccepter = u) OR (f.friendAccepter = :user AND f.friendRequester = u)')
            ->orderBy('r.createdAt', 'DESC')
            ->setParameter('status', 'accepted')
            ->setParameter('user', $userId);

        return $qb->getQuery()->getResult();
        //   return $this->createQueryBuilder('review')
        //       ->select('book.id AS bookId', 'book.title', 'review.rating', 'review.comment', 'review.createdAt')
        //       ->join('review.book', 'book')
        //       ->join('review.user', 'user')
        //       ->join('user.friendshipRequests', 'requester', 'WITH', 'requester.friendshipAccepter = :userId AND requester.isAccepted = true')
        //       ->join('user.friendshipAccepters', 'accepter', 'WITH', 'accepter.friendshipRequester = :userId AND accepter.isAccepted = true')
        //       ->andWhere('requester.id IS NOT NULL OR accepter.id IS NOT NULL')
        //       ->setParameter('userId', $userId)
        //       ->orderBy('review.createdAt', 'DESC')
        //       ->getQuery()
        //       ->getResult();
    }

    //    /**
    //     * @return Review[] Returns an array of Review objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Review
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
