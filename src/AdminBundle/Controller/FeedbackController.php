<?php

namespace AdminBundle\Controller;


use WebBundle\Entity\Feedback;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class CityController
 * @package AdminBundle\Controller
 */
class FeedbackController extends Controller
{
    /**
     * @Route("/rooms/{slug}/feedbacks", name="admin_feedbacks_list")
     * @Method("GET")
     * @param $slug
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($slug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $feedbacks = $em->getRepository('WebBundle:Feedback')->findAllFeedbacksByRoom($slug);
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $feedbacks,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('knp_paginator.page_range')),
            ['defaultSortFieldName' => 'f.id', 'defaultSortDirection' => 'desc']
        );
        return $this->render('AdminBundle:Feedback:list.html.twig', [
            'feedbacks' => $result,
        ]);
    }

    /**
     * @Route("/feedbacks/{id}/activate", name="admin_feedbacks_activate")
     * @param Request $request
     * @param Feedback $feedback
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function activateAction(Request $request, Feedback $feedback)
    {
        $feedback->setIsActive(!$feedback->getIsActive());
        $status = $feedback->getIsActive() ? 'activated' : 'deactivated';
        $em = $this->getDoctrine()->getManager();
        $em->persist($feedback);
        $em->flush();
        $this->addFlash('success', sprintf('Feedback is %s.', $status));
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/feedbacks/{id}/delete", name="admin_feedbacks_delete")
     * @param Request $request
     * @param Feedback $feedback
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, Feedback $feedback)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($feedback);
        $em->flush();
        $this->addFlash('success', 'Feedback is deleted.');
        return $this->redirect($request->headers->get('referer'));
    }
}
