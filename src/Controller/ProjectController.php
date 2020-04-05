<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
            'tasks_user_done' => $projectRepository->getTasksUserDone($user),
            'users' => $projectRepository->getUsersProject()
        ]);
    }

    /**
     * @Route("/new/{redirect}", name="project_new", methods={"GET","POST"})
     */
    public function new(Request $request, string $redirect): Response
    {
        $user = $this->get('security.token_storage')
                    ->getToken()
                    ->getUser();
        $project = new Project($user);
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute($redirect);
        }

        return $this->render('project/form/new.html.twig', [
            'project' => $project,
            'redirect' => $redirect,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit/{redirect}", name="project_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Project $project, string $redirect): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Project updated!'
            );
            return $this->redirectToRoute($redirect);
        }

        return $this->render('project/form/edit.html.twig', [
            'project' => $project,
            'redirect' => $redirect,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete/", name="project_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Project $project): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($project);
        $entityManager->flush();

        $this->addFlash(
            'warning',
            'Project deleted!'
        );

        return new Response();
    }
}