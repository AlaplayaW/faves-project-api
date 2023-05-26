<?php

// src/DataFixtures/UserFixtures.php

namespace App\DataFixtures;

// use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
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

      // Création des autres utilisateurs
      for ($i = 0; $i < 25; $i++) {
          $user = new User();
          $user->setEmail('user' . $i . '@example.com');
          $user->setRoles(['ROLE_USER']);
          $user->setPassword($this->hasher->hashPassword($user, 'password' . $i));
          $user->setFirstname('First name ' . $i);
          $user->setLastname('Last name ' . $i);
          $user->setUsername('username' . $i);
          $user->setPhone('1234567890');

          // On peut ajouter le média si besoin

          $manager->persist($user);
      }

      $manager->flush();
  }
}
