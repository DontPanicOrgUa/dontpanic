<?php

namespace WebBundle\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShareController extends Controller
{
    /**
     * @Route("/shares", name="web_shares_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $shares = $em->getRepository('WebBundle:Share')->findAll();
        $cities = $em->getRepository('WebBundle:City')->findAllWithActiveRooms();
        $menu = $em->getRepository('WebBundle:Page')->findBy(['isInMenu' => true]);
        return $this->render('WebBundle:Share:list.html.twig', [
            'shares' => $shares,
            'menu' => $menu,
            'cities' => $cities
        ]);
    }
}