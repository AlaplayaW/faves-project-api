<?php

// src/DataFixtures/UserFixtures.php

namespace App\DataFixtures;

// use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

use App\Entity\Book;
use App\Entity\Friendship;
use App\Entity\Genre;
use App\Entity\BookGenre;
use App\Entity\Media;
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
    $admin->setPseudo('admin');

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
      $user->setPseudo('userName' . $i);

      // Générer un média associé à l'utilisateur
      $media = new Media();
      // Generate a random image URL using Lorem Picsum
      $imageWidth = 200; // Adjust image width as needed
      $imageHeight = 200; // Adjust image height as needed
      $imageUrl = "https://picsum.photos/{$imageWidth}/{$imageHeight}?image={$i}";
      $media->setImageUrl($imageUrl);
      $user->setMedia($media);

      $manager->persist($user);

      // Ajouter une référence à chaque utilisateur
      $this->addReference('user_' . $i, $user);
    }

    // Générer 20 books
    for ($i = 1; $i <= 20; $i++) {
      $book = new Book();
      $book->setTitle("Title{$i}");
      $book->setSubtitle("Subtitle{$i}");
      $book->setDescription("Description{$i}");
      $book->setUser($this->getReference('user_' . rand(1, 20)));
      $book->setAuthors(["Author{$i}"]);
      $book->setPublisher("Publisher{$i}");
      $book->setPublishedDate(DateTime::createFromFormat('Y-m-d', "2023-05-01"));
      $book->setPageCount($i * 10);
      $manager->persist($book);

      // Générer un média associé à un book (livre ou film)
      $media = new Media();
      $media->setImageUrl("https://example.com/image{$i}.jpg");
      $book->setMedia($media);

      // Générer des genres associés à l'book
      for ($j = 1; $j <= 3; $j++) {
        $genre = new Genre();
        $genre->setName("Genre{$j}");
        $manager->persist($genre);

        $bookGenre = new BookGenre();
        $bookGenre->setBook($book);
        $bookGenre->setGenre($genre);
        $manager->persist($bookGenre);
      }

      $manager->persist($book);
      $this->addReference('book_' . $i, $book);
    }

    // Générer 20 amitiés entre utilisateurs
    for ($i = 2; $i <= 20; $i++) {
      $friendship = new Friendship();
      $requester = $this->getReference('user_' . $i); // Utilisateur qui envoie la demande
      $accepter = $this->getReference('user_' . rand(1, 20)); // Utilisateur qui accepte la demande
      $friendship->setFriendRequester($requester);
      $friendship->setFriendAccepter($accepter);

      $manager->persist($friendship);
    }

    // Générer 20 avis
    for ($i = 1; $i <= 20; $i++) {
      $review = new Review();
      $review->setRating(rand(1, 10));
      $review->setComment("Comment{$i}");
      $book = $this->getReference('book_' . $i); // Référence à un book existant
      $review->setBook($book);
      $user = $this->getReference('user_' . rand(1, 20)); // Référence à un utilisateur existant
      $review->setUser($user);

      $manager->persist($review);
    }

    $manager->flush();
  }

}
