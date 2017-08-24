<?php

namespace AdminBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use WebBundle\Entity\Callback as WebBundleCallback;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class CityController
 * @package AdminBundle\Controller
 */
class CallbackController extends Controller
{
    /**
     * @Route("/callbacks", name="admin_callbacks_list")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $callbacks = $em->getRepository('WebBundle:Callback')->findAll();
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $callbacks,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('knp_paginator.page_range')),
            ['defaultSortFieldName' => 'c.id', 'defaultSortDirection' => 'desc']
        );
        return $this->render('AdminBundle:Callback:list.html.twig', [
            'callbacks' => $result,
        ]);
    }

    /**
     * @Route("/callbacks/{id}/process", name="admin_callbacks_process")
     * @param Request $request
     * @param WebBundleCallback $callback
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function processAction(Request $request, WebBundleCallback $callback)
    {
        $callback->setIsProcessed(!$callback->getIsProcessed());
        $status = $callback->getIsProcessed() ? 'processed' : 'set as not processed';
        $em = $this->getDoctrine()->getManager();
        $em->persist($callback);
        $em->flush();
        $this->addFlash('success', sprintf('Callback is %s.', $status));
        return $this->redirect($request->headers->get('referer'));
    }
}
