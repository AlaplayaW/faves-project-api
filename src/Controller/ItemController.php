<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\ItemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class ItemController extends AbstractController
{

    public function __construct(
        private ItemService $itemService,
        private UserRepository $userRepository
    ) {
    }


    // #[Route('/items/friends', methods: ['GET'], name: 'get_friends_items')]
    // public function getFriendsItems(): JsonResponse
    // {
    //     // $connectedUser = $this->getUser();
    //     // $userEmail = $connectedUser->getUserIdentifier();

    //     // Récupérer un autre utilisateur par son id (exemple)
    //     $user = $this->userRepository->findOneBy(['id' => 1]);

    //     // Utilisez le UserRepository pour récupérer des utilisateurs en fonction de certains critères
    //     // $userByEmail = $this->userRepository->findOneBy(['email' => $userEmail]);

    //     // Utilisez le service pour obtenir les items postés par les amis
    //     $items = $this->itemService->getFriendsItems($user);

    //     // Supprimez les doublons d'items (au cas où un item est partagé par plusieurs amis)
    //     // $uniqueItems = array_unique($items, SORT_REGULAR);

    //     return $this->json($items);
    // }
}
