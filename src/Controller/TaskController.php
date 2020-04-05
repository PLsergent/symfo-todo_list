<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Project;
use App\Form\TaskType;
use App\Form\NewTaskType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/task")
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/", name="task_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository)
    {
        $user = $this->get('security.token_storage')
                    ->getToken()
                    ->getUser();
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
            'tasks' => $userRepository->getCurrentTasksSortByDate($user),
            'tasks_done' => $userRepository->getDoneTasksSortByDate($user),
        ]);
    }

    /**
     * @Route("/new/{id}/{redirect}", name="task_new", methods={"GET","POST"})
     */
    public function new(Request $request, Project $project, string $redirect): Response
    {
        $task = new Task($project);
        $form = $this->createForm(NewTaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute($redirect);
        }

        return $this->render('task/form/new.html.twig', [
            'task' => $task,
            'project' => $project,
            'redirect' => $redirect,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/done", name="task_done", methods={"POST"})
     */
    public function complete(Task $task): Response
    {
        if (!$task->getDone()) {
            $task->setDone(true);
            $this->getDoctrine()->getManager()->flush();
        }
        return new Response();
    }

    /**
     * @Route("/{id}/restore", name="task_restore", methods={"POST"})
     */
    public function restore(Task $task): Response
    {
        if ($task->getDone()) {
            $task->setDone(false);
            $this->getDoctrine()->getManager()->flush();
        }
        return new Response();
    }

    /**
     * @Route("/{id}/edit/{redirect}", name="task_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Task $task, string $redirect): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Task updated!'
            );
            return $this->redirectToRoute($redirect);
        }

        return $this->render('task/form/edit.html.twig', [
            'task' => $task,
            'redirect' => $redirect,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete/", name="task_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Task $task): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($task);
        $entityManager->flush();

        $this->addFlash(
            'warning',
            'Task deleted!'
        );

        return new Response();
    }
}