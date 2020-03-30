<?php

namespace App\Controller\Admin;

use App\Entity\Todo;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/todo")
 */
class AdminTodoController extends AbstractController
{
    /**
     * @Route("/", name="admin_todo_index", methods={"GET"})
     */
    public function index(TodoRepository $todoRepository): Response
    {
        return $this->render('admin/todo/index.html.twig', [
            'todos' => $todoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_todo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $todo = new Todo();
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($todo);
            $entityManager->flush();

            return $this->redirectToRoute('admin_todo_index');
        }

        return $this->render('admin/todo/new.html.twig', [
            'todo' => $todo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_todo_show", methods={"GET"})
     */
    public function show(Todo $todo): Response
    {
        return $this->render('admin/todo/show.html.twig', [
            'todo' => $todo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_todo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Todo $todo): Response
    {
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_todo_index');
        }

        return $this->render('admin/todo/edit.html.twig', [
            'todo' => $todo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_todo_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Todo $todo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$todo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($todo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_todo_index');
    }
}
