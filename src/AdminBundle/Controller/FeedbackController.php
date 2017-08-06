<?php

namespace AdminBundle\Controller;


use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use WebBundle\Entity\Feedback;

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
        $feedbacks = $em->getRepository('WebBundle:Feedback')
            ->findAllFeedbacksByRoom($slug);

        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $feedbacks,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('knp_paginator.page_range'))
        );

        return $this->render('AdminBundle:Feedback:list.html.twig', [
            'feedbacks' => $result
        ]);
    }

    /**
     * @Route("/cities/add", name="admin_cities_add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(CityFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $city = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($city);
            $em->flush();
            $this->addFlash('success', 'New city is added.');
            return $this->redirectToRoute('admin_cities_list');
        }

        return $this->render('AdminBundle:City:add.html.twig', [
            'cityForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/feedbacks/{id}/activate", name="admin_feedbacks_activate")
     * @param Feedback $feedback
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function activateAction(Feedback $feedback)
    {
        $feedback->setIsActive(!$feedback->getIsActive());
        $status = $feedback->getIsActive() ? 'activated' : 'deactivated';
        $em = $this->getDoctrine()->getManager();
        $em->persist($feedback);
        $em->flush();
        $this->addFlash('success', sprintf('Feedback is %s.', $status));
        return $this->redirectToRoute('admin_feedbacks_list', [
            'slug' => $feedback->getRoom()->getSlug()
        ]);
    }

    /**
     * @Route("/feedbacks/{id}/delete", name="admin_feedbacks_delete")
     * @param Feedback $feedback
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Feedback $feedback)
    {
        $slug = $feedback->getRoom()->getSlug();
        $em = $this->getDoctrine()->getManager();
        $em->remove($feedback);
        $em->flush();
        $this->addFlash('success', 'Feedback is deleted.');
        return $this->redirectToRoute('admin_feedbacks_list', [
            'slug' => $slug
        ]);
    }
}
