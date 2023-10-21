<?php

namespace App\Repository;

use App\Entity\Friendship;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Friendship>
 *
 * @method Friendship|null find($id, $lockMode = null, $lockVersion = null)
 * @method Friendship|null findOneBy(array $criteria, array $orderBy = null)
 * @method Friendship[]    findAll()
 * @method Friendship[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FriendshipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Friendship::class);
    }

    public function save(Friendship $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Friendship $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findFriendsByNetwork(int $userId): array
    {
        // Code pour récupérer la liste d'amis validés de l'utilisateur connecté

        // Exemple de requête (à personnaliser en fonction de votre modèle de données)
        $qb = $this->createQueryBuilder('f')
            ->where('f.status = :status')
            ->andWhere('f.friendRequester = :user OR f.friendAccepter = :user')
            ->setParameter('status', 'accepted')
            ->setParameter('user', $userId);

        return $qb->getQuery()->getResult();
    }

    public function findFriendRequests(int $userId): array
{
    $qb = $this->createQueryBuilder('f')
        ->where('f.status = :status')
        ->andWhere('f.friendRequester = :user')
        ->setParameter('status', 'pending')
        ->setParameter('user', $userId);

    return $qb->getQuery()->getResult();
}

public function findAcceptedOrPendingFriendships(int $userId): array
{
    $qb = $this->createQueryBuilder('f')
        ->andWhere('f.friendRequester = :user OR f.friendAccepter = :user')
        ->andWhere('f.status = :accepted OR f.status = :pending')
        ->setParameters([
            'user' => $userId,
            'accepted' => Friendship::STATUS_ACCEPTED,
            'pending' => Friendship::STATUS_PENDING,
        ]);

    return $qb->getQuery()->getResult();
}


// public function findReviewsByUserFriends(int $userId): array
// {
//     // Code pour récupérer la liste des critiques de livres commentées par des amis de l'utilisateur connecté

//     // Exemple de requête (à personnaliser en fonction de votre modèle de données)
//     $qb = $this->createQueryBuilder('b')
//         ->join('b.reviews', 'r')
//         ->join('r.user', 'u')
//         ->join('u.friendAccepters', 'f')
//         ->where('f.status = :status')
//         ->andWhere('(f.friendRequester = :user AND f.friendAccepter = u) OR (f.friendAccepter = :user AND f.friendRequester = u)')
//         ->setParameter('status', 'accepted')
//         ->setParameter('user', $userId);

//     return $qb->getQuery()->getResult();
// }


//     public function findBooksWithDetailsByFriends(array $friendIds)
//     {
//         return $this->createQueryBuilder('item')
//             ->select('item', 'movie', 'book')
//             ->leftJoin('item.movie', 'movie')
//             ->leftJoin('item.book', 'book')
//             ->join('item.user', 'user')
//             ->where('user.id IN (:friendIds)')
//             ->setParameter('friendIds', $friendIds)
//             ->getQuery()
//             ->getResult();
//     }

// /**
//  * Get friends' data by user ID.
//  *
//  * @param int $userId
//  * @return User[] The objects.
//  */
// public function findFriendsByUserId(int $userId): array
// {
//     $entityManager = $this->getEntityManager();

//     $dql = '
//         SELECT u
//         FROM App\Entity\User u
//         JOIN App\Entity\Friendship f WITH (u.id = f.friendshipRequester OR u.id = f.friendshipAccepter)
//         WHERE f.isAccepted = true
//         AND u.id <> :userId
//         AND (f.friendshipRequester = :userId OR f.friendshipAccepter = :userId)
//     ';

//     $query = $entityManager->createQuery($dql);
//     $query->setParameter('userId', $userId);

//     return $query->getResult();
// }


    //    /**
    //     * @return Friendship[] Returns an array of Friendship objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Friendship
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
