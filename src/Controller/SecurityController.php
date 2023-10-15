<?php
namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{

  private UserService $userService;

  public function __construct(
      UserService $userService,
  ) {
      $this->userService = $userService;
  }

    #[Route('/fv1-api/login', methods: ['POST'], name: 'api_login')]
    public function login()
    {
        $user = $this->getUser();

        return $this->json([
          'username' => $user->getUserIdentifier(),
          'roles' => $user->getRoles(),
        ]);
    }

    #[Route('/fv1-api/logout', methods: ['POST'], name: 'api_logout')]
    public function logout()
    {}


    // #[Route('/fv1-api/signup', methods: ['POST'], name: 'api_signup')]
    // public function signup()
    // {}

}
