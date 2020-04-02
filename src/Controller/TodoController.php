<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
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
    public function index()
    {
        return $this->render('todo/index.html.twig', [
            'controller_name' => 'TodoController',
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
}