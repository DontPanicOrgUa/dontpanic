<?php

namespace AdminBundle\Controller;


use AdminBundle\Form\CustomerFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use WebBundle\Entity\Customer;

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

    /**
     * @Route("/customers/{id}/edit", name="admin_customers_edit")
     * @param Request $request
     * @param Customer $customer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Customer $customer)
    {
        $form = $this->createForm(CustomerFormType::class, $customer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();
            $this->addFlash('success', 'Customer is edited.');
            return $this->redirectToRoute('admin_customers_list');
        }

        return $this->render('AdminBundle:Customer:edit.html.twig', [
            'customerForm' => $form->createView()
        ]);
    }
}
