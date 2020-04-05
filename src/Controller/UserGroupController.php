<?php

namespace App\Controller;

use App\Entity\UserGroup;
use App\Entity\Project;
use App\Form\UserGroupType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/user_group")
 */
class UserGroupController extends AbstractController
{
    /**
     * @Route("/new/{id}/{redirect}", name="user_group_new", methods={"GET","POST"})
     */
    public function new(Request $request, Project $project, string $redirect): Response
    {
        $user_group = new UserGroup($project);
        $form = $this->createForm(UserGroupType::class, $user_group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user_group);
            $entityManager->flush();

            return $this->redirectToRoute($redirect);
        }

        return $this->render('user_group/form/new.html.twig', [
            'user_group' => $user_group,
            'project' => $project,
            'redirect' => $redirect,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit/{redirect}", name="user_group_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserGroup $user_group, string $redirect): Response
    {
        $form = $this->createForm(UserGroupType::class, $user_group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'UserGroup updated!'
            );
            return $this->redirectToRoute($redirect);
        }

        return $this->render('user_group/form/edit.html.twig', [
            'user_group' => $user_group,
            'redirect' => $redirect,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete/", name="user_group_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserGroup $user_group): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user_group);
        $entityManager->flush();

        $this->addFlash(
            'warning',
            'UserGroup deleted!'
        );

        return new Response();
    }
}