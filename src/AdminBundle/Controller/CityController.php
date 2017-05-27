<?php

namespace AdminBundle\Controller;

use WebBundle\Entity\City;
use AdminBundle\Form\CityFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class CityController extends Controller
{
    /**
     * @Route("/cities", name="admin_cities_list")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cities = $em->getRepository('WebBundle:City')->findAll();

        $paginator  = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $cities,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('records_per_page'))
        );

        return $this->render('AdminBundle:City:list.html.twig', [
            'cities' => $result
        ]);
    }

    /**
     * @Route("/cities/add", name="admin_cities_add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(CityFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $city = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($city);
            $em->flush();
            $this->addFlash('success', 'New city is added.');
            return $this->redirectToRoute('admin_cities_list');
        }

        return $this->render('AdminBundle:City:add.html.twig', [
            'cityForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/cities/{id}/edit", name="admin_cities_edit")
     * @param Request $request
     * @param City $city
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, City $city)
    {
        $form = $this->createForm(CityFormType::class, $city);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $city = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($city);
            $em->flush();
            $this->addFlash('success', 'City is edited.');
            return $this->redirectToRoute('admin_cities_list');
        }

        return $this->render('AdminBundle:City:edit.html.twig', [
            'cityForm' => $form->createView()
        ]);
    }

    /**
     * @Route("cities/{id}/delete", name="admin_cities_delete")
     * @param City $city
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(City $city)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($city);
        $em->flush();
        $this->addFlash('success', 'City is deleted.');
        return $this->redirectToRoute('admin_cities_list');
    }
}
