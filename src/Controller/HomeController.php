<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(UserRepository $userRepository)
    {
        $user = $this->get('security.token_storage')
                    ->getToken()
                    ->getUser();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'todos' => $userRepository->getCurrentTodosSortByDate($user),
            'tasks' => $userRepository->getCurrentTasksSortByDate($user),
            'todos_done' => $userRepository->getDoneTodosSortByDate($user),
            'tasks_done' => $userRepository->getDoneTasksSortByDate($user),
        ]);
    }
}
