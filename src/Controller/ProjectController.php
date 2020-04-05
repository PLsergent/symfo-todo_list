<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project")
 */
class ProjectController extends AbstractController
{
    /**
     * @Route("/", name="project_index", methods={"GET"})
     */
    public function index(ProjectRepository $projectRepository)
    {
        $user = $this->get('security.token_storage')
                    ->getToken()
                    ->getUser();
        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
            'projects' => $projectRepository->getProjects($user),
            'tasks_manager' => $projectRepository->getTasksManagerCurrent(),
            'tasks_manager_done' => $projectRepository->getTasksManagerDone(),
            'tasks_user' => $projectRepository->getTasksUserCurrent($user),
            'tasks_user_done' => $projectRepository->getTasksUserDone($user)
        ]);
    }
}