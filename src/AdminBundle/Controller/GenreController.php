<?php

namespace AdminBundle\Controller;


use WebBundle\Entity\Genre;
use AdminBundle\Form\GenreFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class GenreController extends Controller
{
    /**
     * @Route("/genres", name="admin_genres_list")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $genres = $em->getRepository('WebBundle:Genre')->findAll();

        $paginator  = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $genres,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('records_per_page'))
        );

        return $this->render('AdminBundle:Genre:list.html.twig', [
            'genres' => $result
        ]);
    }

    /**
     * @Route("/genres/add", name="admin_genres_add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(GenreFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $genre = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($genre);
            $em->flush();
            $this->addFlash('success', 'New genre is added.');
            return $this->redirectToRoute('admin_genres_list');
        }

        return $this->render('AdminBundle:Genre:add.html.twig', [
            'genreForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/genres/{id}/edit", name="admin_genres_edit")
     * @param Request $request
     * @param Genre $genre
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Genre $genre)
    {
        $form = $this->createForm(GenreFormType::class, $genre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $genre = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($genre);
            $em->flush();
            $this->addFlash('success', 'Genre is edited.');
            return $this->redirectToRoute('admin_genres_list');
        }

        return $this->render('AdminBundle:Genre:edit.html.twig', [
            'genreForm' => $form->createView()
        ]);
    }

    /**
     * @Route("genres/{id}/delete", name="admin_genres_delete")
     * @param Genre $genre
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Genre $genre)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($genre);
        $em->flush();
        $this->addFlash('success', 'Genre is deleted.');
        return $this->redirectToRoute('admin_genres_list');
    }
}
