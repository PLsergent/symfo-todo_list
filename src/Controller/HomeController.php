<?php

namespace App\Controller;

use App\Repository\TodoRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(TodoRepository $todoRepository, TaskRepository $taskRepository)
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'todos' => $todoRepository->findAllSortByDate(),
            'tasks' => $taskRepository->findAllSortByDate()
        ]);
    }
}
