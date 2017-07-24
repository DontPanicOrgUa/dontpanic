<?php

namespace WebBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use WebBundle\Entity\Room;

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
        $cityName = $request->query->get('city');
        $cities = $em->getRepository('WebBundle:City')->findAllWithActiveRooms();
        $rooms = $em->getRepository('WebBundle:Room')->findAllByCity($cityName);
        $currentCity = $this->get('translator')->trans('All cities');
        /** @var $rooms Room[] */
        if ($cityName && $rooms) {
            $currentCity = $rooms[0]->getCity()->getName();
        }
        return $this->render('WebBundle:City:list.html.twig', [
            'cities' => $cities,
            'currentCity' => $currentCity,
            'rooms' => $rooms
        ]);
    }
}