<?php

namespace WebBundle\Controller;


use RoomBundle\Entity\City;
use RoomBundle\Entity\Room;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use WebBundle\Entity\Page;
use WebBundle\Entity\Share;

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
        $sort = $request->query->get('sort');
        $order = $request->query->get('order');
        $cities = $em->getRepository(City::class)->findAllWithActiveRooms();
        $menu = $em->getRepository(Page::class)->findBy(['isInMenu' => true]);
        $shares = $em->getRepository(Share::class)->findAll();
        $rooms = $em->getRepository(Room::class)->findAllByCity($cityName, $sort, $order);
        $currentCity = $this->get('translator')->trans('all.cities');
        /** @var $rooms Room[] */
        if ($cityName && $rooms) {
            $currentCity = $rooms[0]['room']->getCity()->getName($request->getLocale());
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