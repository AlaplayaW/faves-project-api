<?php

namespace App\Repository;

use App\Entity\Genre;
use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 *
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function save(Item $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Item $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Genre[] Returns an array of Genre objects for an Item ID
     */
    public function findGenresByItem($itemId)
    {
        return $this->createQueryBuilder('i')
            ->select('g')
            ->leftJoin('i.itemGenres', 'ig')
            ->leftJoin('ig.genre', 'g')
            ->where('i.id = :itemId')
            ->setParameter('itemId', $itemId)
            ->getQuery()
            ->getResult();
    }

    // A voir si utile ou non
    public function findItemsWithMovieDetails()
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.movie', 'm')
            ->addSelect('m')
            ->getQuery()
            ->getResult();
    }

    // A voir si utile ou non
    public function findItemsWithBookDetails()
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.book', 'b')
            ->addSelect('b')
            ->getQuery()
            ->getResult();
    }

    // A voir si utile ou non
    public function findItemsWithDetails()
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.movie', 'm')
            ->leftJoin('i.book', 'b')
            ->addSelect('CASE WHEN i.mediaType = :movieType THEN m ELSE b END AS details')
            ->setParameter('movieType', 'movie')
            ->getQuery()
            ->getResult();
    }


    public function findByFriendIds(array $friendIds)
    {
        return $this->createQueryBuilder('item')
            ->select('item', 'movie', 'book')
            ->leftJoin('item.movie', 'movie')
            ->leftJoin('item.book', 'book')
            ->join('item.postedBy', 'user')
            ->where('user.id IN (:friendIds)')
            ->setParameter('friendIds', $friendIds)
            ->getQuery()
            ->getResult();
    }

    public function findByCurrentUserFriends(int $userId)
    {
        return $this->createQueryBuilder('item')
            ->select('item', 'movie', 'book')
            ->leftJoin('item.movie', 'movie')
            ->leftJoin('item.book', 'book')
            ->join('item.postedBy', 'user')
            ->join('user.friends', 'friend')
            ->where('friend.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Item[] Returns an array of Item objects
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

    //    public function findOneBySomeField($value): ?Item
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
