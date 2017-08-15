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
        $shares = $em->getRepository('WebBundle:Share')->findAll();
        $rooms = $em->getRepository('WebBundle:Room')->findAllByCity($cityName);
        $currentCity = $this->get('translator')->trans('all.cities');
        /** @var $rooms Room[] */
        if ($cityName && $rooms) {
            $currentCity = $rooms[0]->getCity()->getName($request->getLocale());
        }
        return $this->render('WebBundle:City:list.html.twig', [
            'cities' => $cities,
            'shares' => $shares,
            'currentCity' => $currentCity,
            'rooms' => $rooms
        ]);
    }
}