<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class CityController extends Controller
{
    /**
     * @Route("/cities", name="admin_cities_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $cities = $em->getRepository('WebBundle:City')->findAll();
        return $this->render('AdminBundle:City:list.html.twig', [
            'cities' => $cities
        ]);
    }
}
