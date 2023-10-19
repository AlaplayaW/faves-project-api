<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;

class CurrentUserController extends AbstractController
{

  public function __construct(
    private Security $security,
    private UserRepository $userRepository
  ) {
  }

  public function __invoke()
  {
    $userFromToken = $this->security->getUser();

    $user = $this->userRepository->findOneBy(['email' => $userFromToken->getUserIdentifier()]);
    return $user;
  }
}
