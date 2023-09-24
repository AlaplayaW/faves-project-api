<?php
namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\UserNotFoundException;
use Symfony\Bundle\SecurityBundle\Security;

class UserService
{
    private UserRepository $userRepository;

    private Security $security;

    public function __construct(UserRepository $userRepository, Security $security )
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
    }

    public function getLoggedIndUser(): User
    {
        $userFromToken = $this->security->getUser();
        dd($userFromToken);
        //Récupérer les informations du user à partir de son username
        $user = $this->userRepository->findOneBy(['email' => $userFromToken->getUserIdentifier()]);

        return $user;
    }


}
