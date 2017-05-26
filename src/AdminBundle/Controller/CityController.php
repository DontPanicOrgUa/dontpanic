<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\CityAddFormType;
use AdminBundle\Form\CityEditFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use WebBundle\Entity\City;
use WebBundle\Entity\CityTranslation;

class CityController extends Controller
{
    /**
     * @Route("/cities", name="admin_cities_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $food = new City();
        $food->setName('Food');
        $food->addTranslation(new CityTranslation('lt', 'title', 'Maistas'));

        $fruits = new City;
        $fruits->setName('Fruits');
        $fruits->addTranslation(new CityTranslation('lt', 'title', 'Vaisiai'));
        $fruits->addTranslation(new CityTranslation('ru', 'title', 'rus trans'));

        $em->persist($food);
        $em->persist($fruits);
        $em->flush();

        die;

        $em = $this->getDoctrine()->getManager();
        $cities = $em->getRepository('WebBundle:City')->findAll();
        return $this->render('AdminBundle:City:list.html.twig', [
            'cities' => $cities
        ]);
    }

    /**
     * @Route("/cities/add", name="admin_cities_add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(CityAddFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $city = new City();
            $city->translate('ru')->setName($data['city_name_ru']);
            $city->translate('en')->setName($data['city_name_en']);
            $city->translate('de')->setName($data['city_name_de']);
            $em->persist($city);
            // In order to persist new translations, call mergeNewTranslations method, before flush
            $city->mergeNewTranslations();
            $em->flush();
            $this->addFlash('success', 'New city is added.');
            return $this->redirectToRoute('admin_cities_list');
        }
        return $this->render('AdminBundle:City:add.html.twig', [
            'cityAddForm' => $form->createView()
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
        $form = $this->createForm(CityEditFormType::class, $city);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $city = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($city);
            $city->mergeNewTranslations();
            $em->flush();
            $this->addFlash('success', 'City is edited.');
            return $this->redirectToRoute('admin_cities_list');
        }
        return $this->render('AdminBundle:City:edit.html.twig', [
            'cityEditForm' => $form->createView()
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
