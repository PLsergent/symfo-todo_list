<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository)
    {
        $user = $this->get('security.token_storage')
                    ->getToken()
                    ->getUser();
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categoryRepository->findAll(),
            'todos' => $categoryRepository->getCurrentTodosPerUser($user),
            'tasks' => $categoryRepository->getCurrentTasksPerUser($user)
        ]);
    }

    /**
     * @Route("/new/{redirect}", name="category_new", methods={"GET","POST"})
     */
    public function new(Request $request, string $redirect): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute($redirect);
        }

        return $this->render('category/form/new.html.twig', [
            'category' => $category,
            'redirect' => $redirect,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit/{redirect}", name="category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category, string $redirect): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'Category updated!'
            );
            return $this->redirectToRoute($redirect);
        }

        return $this->render('category/form/edit.html.twig', [
            'category' => $category,
            'redirect' => $redirect,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete/", name="category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash(
            'warning',
            'Category deleted!'
        );

        return new Response();
    }
}