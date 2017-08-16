<?php

namespace WebBundle\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    /**
     * @Route("/_{slug}", name="web_page_view")
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('WebBundle:Page')->findOneBySlug($slug);
        $menu = $em->getRepository('WebBundle:Page')->findBy(['isInMenu' => true]);
        if (!$page) {
            throw $this->createNotFoundException('Page does not found!');
        }
        $cities = $em->getRepository('WebBundle:City')->findAllWithActiveRooms();
        return $this->render('WebBundle:Page:view.html.twig', [
            'page' => $page,
            'menu' => $menu,
            'cities' => $cities
        ]);
    }
}