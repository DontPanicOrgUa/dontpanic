<?php

namespace WebBundle\Controller;


use RoomBundle\Entity\City;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use WebBundle\Entity\Page;
use WebBundle\Entity\Share;

class ShareController extends Controller
{
    /**
     * @Route("/shares", name="web_shares_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $shares = $em->getRepository(Share::class)->findAll();
        $cities = $em->getRepository(City::class)->findAllWithActiveRooms();
        $menu = $em->getRepository(Page::class)->findBy(['isInMenu' => true]);
        return $this->render('WebBundle:Share:list.html.twig', [
            'shares' => $shares,
            'menu' => $menu,
            'cities' => $cities
        ]);
    }
}