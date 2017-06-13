<?php
/**
 * Created by PhpStorm.
 * User: mykyta
 * Date: 5/28/17
 * Time: 4:24 PM
 */

namespace WebBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $request->query->get('city') ?: 'kiev';
        /** @var $city \WebBundle\Entity\City */
        $city = $em->getRepository('WebBundle:City')
            ->findOneBy(['nameEn' => $city]);
        return $this->render('WebBundle:City:list.html.twig', [
            'city' => $city
        ]);
    }

    /**
     * @Route("/second", name="web_second")
     */
    public function secondAction()
    {
        dump(123);die;
    }
}