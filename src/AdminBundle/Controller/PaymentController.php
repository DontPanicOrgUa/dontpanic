<?php

namespace AdminBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class PaymentController extends Controller
{
    /**
     * @Route("/payments", name="admin_payments_list")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
//        $paymentsQuery = $em
//            ->getRepository('WebBundle:Payment')
//            ->createQueryBuilder('p')
//            ->getQuery();
        $payments = $em->getRepository('WebBundle:Payment')->findAll();
        $paginator  = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $payments,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 5)
        );

        return $this->render('AdminBundle:Payment:list.html.twig', [
            'payments' => $result
        ]);
    }
}
