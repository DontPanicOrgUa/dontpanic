<?php

namespace AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class PaymentController extends Controller
{
    /**
     * @Route("/payments", name="admin_payments_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $payments = $em->getRepository('WebBundle:Payment')->findAll();
        return $this->render('AdminBundle:Payment:list.html.twig', [
            'payments' => $payments
        ]);
    }
}
