<?php

namespace WebBundle\Controller;


use WebBundle\Entity\Room;
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
        $cityName = $request->query->get('city');
        $cities = $em->getRepository('WebBundle:City')->findAllWithActiveRooms();
        $menu = $em->getRepository('WebBundle:Page')->findBy(['isInMenu' => true]);
        $shares = $em->getRepository('WebBundle:Share')->findAll();
        $rooms = $em->getRepository('WebBundle:Room')->findAllByCity($cityName);
        $currentCity = $this->get('translator')->trans('all.cities');
        /** @var $rooms Room[] */
        if ($cityName && $rooms) {
            $currentCity = $rooms[0]->getCity()->getName($request->getLocale());
        }
        return $this->render('WebBundle:Default:list.html.twig', [
            'cities' => $cities,
            'menu' => $menu,
            'shares' => $shares,
            'currentCity' => $currentCity,
            'rooms' => $rooms
        ]);
    }
}