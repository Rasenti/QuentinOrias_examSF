<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('home/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/home/{id}', name: 'app_details', methods: ['GET'])]
    public function details(User $user): Response
    {
        return $this->render('home/details.html.twig', [
            'user' => $user,
        ]);
    }
}
