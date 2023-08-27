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

      /**
   * Récupère toutes les reviews postées par les amis du user connecté.
   *
   * @param int $userId Liste des IDs des amis du user connecté
   * @return Review[] Tableau contenant les reviews postées par les amis
   */
  public function findReviewsByUserFriends(int $userId): array
  {
      $entityManager = $this->getEntityManager();

      $dql = '
          SELECT r
          FROM App\Entity\Review r
          JOIN App\Entity\User u WITH r.postedBy = u.id
          JOIN App\Entity\Friendship f WITH (u.id = f.friendshipRequester OR u.id = f.friendshipAccepter)
          WHERE f.isAccepted = true
          AND u.id <> :userId
          AND (f.friendshipRequester = :userId OR f.friendshipAccepter = :userId)
      ';

      $query = $entityManager->createQuery($dql);
      $query->setParameter('userId', $userId);

      return $query->getResult();
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
