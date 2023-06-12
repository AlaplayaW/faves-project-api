<?php

// src/DataFixtures/UserFixtures.php

namespace App\DataFixtures;

// use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

use App\Entity\Book;
use App\Entity\Friendship;
use App\Entity\Genre;
use App\Entity\Item;
use App\Entity\ItemGenre;
use App\Entity\Media;
use App\Entity\Movie;
use App\Entity\Review;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use DateTime;

class DataFixtures extends Fixture
{
  private UserPasswordHasherInterface $hasher;

  public function __construct(UserPasswordHasherInterface $hasher)
  {
    $this->hasher = $hasher;
  }

  public function load(ObjectManager $manager)
  {
    // Création de l'utilisateur SUPER_ADMIN
    $admin = new User();
    $admin->setEmail('admin@example.com');
    $admin->setRoles(['ROLE_SUPER_ADMIN']);
    $admin->setPassword($this->hasher->hashPassword($admin, 'adminpassword'));
    $admin->setFirstname('Admin');
    $admin->setLastname('User');
    $admin->setUsername('admin');
    $admin->setPhone('1234567890');

    
    // On peut ajouter le média si besoin
    $manager->persist($admin);
    
    // Ajouter une référence à l'utilisateur super admin
    $this->addReference('admin_user', $admin);

    // Création des autres utilisateurs
    for ($i = 1; $i < 26; $i++) {
      $user = new User();
      $user->setEmail('user' . $i . '@example.com');
      $user->setRoles(['ROLE_USER']);
      $user->setPassword($this->hasher->hashPassword($user, 'password' . $i));
      $user->setFirstname('First name ' . $i);
      $user->setLastname('Last name ' . $i);
      $user->setUsername('username' . $i);
      $user->setPhone('1234567890');

      // Générer un média associé à l'utilisateur
      $media = new Media();
      $media->setImageUrl("https://example.com/image{$i}.jpg");
      $user->setUserMedia($media);

      $manager->persist($user);

      // Ajouter une référence à chaque utilisateur
      $this->addReference('user_' . $i, $user);

    }

    // Générer 20 items (films ou livres)
    for ($i = 1; $i <= 20; $i++) {
      $item = new Item();
      $item->setMediaType("mediaType{$i}");
      $item->setTitle("Title{$i}");
      $item->setSubtitle("Subtitle{$i}");
      $item->setDescription("Description{$i}");
      $item->setPostedBy($this->getReference('user_' . rand(1, 20)));

      // Générer un média associé à un item (livre ou film)
      $media = new Media();
      $media->setImageUrl("https://example.com/image{$i}.jpg");
      $item->setMedia($media);

      // Générer des genres associés à l'item
      for ($j = 1; $j <= 3; $j++) {
        $genre = new Genre();
        $genre->setName("Genre{$j}");
        $manager->persist($genre);

        $itemGenre = new ItemGenre();
        $itemGenre->setName("ItemGenre{$j}");
        $itemGenre->setItem($item);
        $itemGenre->setGenre($genre);
        $manager->persist($itemGenre);
      }

      // Générer un livre associé à l'item
      $book = new Book();
      $book->setAuthors(["Author{$i}"]);
      $book->setPublisher("Publisher{$i}");
      $book->setPublishedDate(\DateTime::createFromFormat('Y-m-d', "2023-05-01"));
      $book->setPageCount($i * 10);
      $book->setItem($item);
      $manager->persist($book);

      // Générer un film associé à l'item
      $movie = new Movie();
      $movie->setType("Type{$i}");
      $movie->setCasting("Cast{$i}");
      $movie->setDirectors("Director{$i}");
      $movie->setYear(\DateTime::createFromFormat('Y', 2000 + $i));
      $movie->setItem($item);
      $manager->persist($movie);

      $manager->persist($item);

      $this->addReference('item_' . $i, $item);

    }

    // Générer 20 amitiés entre utilisateurs
    for ($i = 2; $i <= 20; $i++) {
      $friendship = new Friendship();
      $requester = $this->getReference('user_' . $i); // Utilisateur qui envoie la demande
      $accepter = $this->getReference('user_' . rand(1, 20)); // Utilisateur qui accepte la demande
      $friendship->setFriendshipRequester($requester);
      $friendship->setFriendshipAccepter($accepter);

      $manager->persist($friendship);
    }

    // Générer 20 avis
    for ($i = 1; $i <= 20; $i++) {
      $review = new Review();
      $review->setRating(rand(1, 10));
      $review->setComment("Comment{$i}");
      $item = $this->getReference('item_' . $i); // Référence à un item existant
      $review->setItem($item);
      $user = $this->getReference('user_' . rand(1, 20)); // Référence à un utilisateur existant
      $review->setPostedBy($user);

      $manager->persist($review);
    }

    $manager->flush();
  }
}
