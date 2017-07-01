<?php
/**
 * Created by PhpStorm.
 * User: mykyta
 * Date: 5/28/17
 * Time: 6:39 PM
 */

namespace AdminBundle\Controller;


use AdminBundle\Entity\User;
use AdminBundle\Form\AddUserFormType;
use AdminBundle\Form\EditUserFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class UserController extends Controller
{
    /**
     * @Route("/users", name="admin_users_list")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cities = $em->getRepository('AdminBundle:User')->findAll();

        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $cities,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('records_per_page'))
        );

        return $this->render('AdminBundle:User:list.html.twig', [
            'users' => $result
        ]);
    }

    /**
     * @Route("/users/add", name="admin_users_add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(AddUserFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'New user is added.');
            return $this->redirectToRoute('admin_users_list');
        }

        return $this->render('AdminBundle:User:add.html.twig', [
            'userForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/users/{id}/edit", name="admin_users_edit")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, User $user)
    {
        $form = $this->createForm(EditUserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            if ($user->getPlainPassword()) {
                $user->setPassword('');
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'User is edited.');
            return $this->redirectToRoute('admin_users_list');
        }

        return $this->render('AdminBundle:User:edit.html.twig', [
            'userForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/users/{id}/delete", name="admin_users_delete")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'User is deleted.');
        return $this->redirectToRoute('admin_users_list');
    }
}