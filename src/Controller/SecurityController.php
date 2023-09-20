<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{

    #[Route('/api/login', methods: ['POST'], name: 'api_login')]
    public function login()
    {
        $user = $this->getUser();

        return $this->json([
          'username' => $user->getUserIdentifier(),
          'roles' => $user->getRoles()
        ]);
    }

    #[Route('/api/logout', methods: ['POST'], name: 'api_logout')]
    public function logout()
    {}

}