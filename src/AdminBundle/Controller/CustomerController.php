<?php

namespace AdminBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class CustomerController
 * @package AdminBundle\Controller
 * @Security("has_role('ROLE_ADMIN')")
 */
class CustomerController extends Controller
{
    /**
     * @Route("/customers", name="admin_customers_list")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $customers = $em->getRepository('WebBundle:Customer')->findAll();

        $paginator  = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $customers,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('knp_paginator.page_range'))
        );

        return $this->render('AdminBundle:Customer:list.html.twig', [
            'customers' => $result
        ]);
    }
}
