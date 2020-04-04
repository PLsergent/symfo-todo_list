<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
use App\Form\NewTodoType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/todo")
 */
class TodoController extends AbstractController
{
    /**
     * @Route("/", name="todo_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository)
    {
        $user = $this->get('security.token_storage')
                    ->getToken()
                    ->getUser();
        return $this->render('todo/index.html.twig', [
            'controller_name' => 'TodoController',
            'todos' => $userRepository->getCurrentTodosSortByDate($user),
            'todos_done' => $userRepository->getDoneTodosSortByDate($user),
        ]);
    }

    /**
     * @Route("/new/{redirect}", name="todo_new", methods={"GET","POST"})
     */
    public function new(Request $request, string $redirect): Response
    {
        $user = $this->get('security.token_storage')
                    ->getToken()
                    ->getUser();
        $todo = new Todo($user);
        $form = $this->createForm(NewTodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($todo);
            $entityManager->flush();

            return $this->redirectToRoute($redirect);
        }

        return $this->render('todo/form/new.html.twig', [
            'todo' => $todo,
            'redirect' => $redirect,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/done", name="todo_done", methods={"POST"})
     */
    public function complete(Todo $todo): Response
    {
        if (!$todo->getDone()) {
            $todo->setDone(true);
            $this->getDoctrine()->getManager()->flush();
        }
        return new Response();
    }

    /**
     * @Route("/{id}/restore", name="todo_restore", methods={"POST"})
     */
    public function restore(Todo $todo): Response
    {
        if ($todo->getDone()) {
            $todo->setDone(false);
            $this->getDoctrine()->getManager()->flush();
        }
        return new Response();
    }

    /**
     * @Route("/{id}/edit/{redirect}", name="todo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Todo $todo, string $redirect): Response
    {
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Todo updated!'
            );
            return $this->redirectToRoute($redirect);
        }

        return $this->render('todo/form/edit.html.twig', [
            'todo' => $todo,
            'redirect' => $redirect,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete/", name="todo_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Todo $todo): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($todo);
        $entityManager->flush();

        $this->addFlash(
            'warning',
            'Todo deleted!'
        );

        return new Response();
    }
}