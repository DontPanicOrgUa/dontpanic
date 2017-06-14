<?php

namespace WebBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home_page")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cityName = $request->query->get('city') ?: 'kiev';
        $cities = $em->getRepository('WebBundle:City')->findAllWithActiveRooms();
        $city = null;
        foreach ($cities as $c) {
            /** @var $c \WebBundle\Entity\City */
            if ($c->getNameEn() == ucfirst($cityName)){
                $city = $c;
                break;
            }
        }
        if (!$city) {
            throw $this->createNotFoundException('City '. $cityName .' not found');
        }
        return $this->render('WebBundle:City:list.html.twig', [
            'cities' => $cities,
            'city' => $city
        ]);
    }
}