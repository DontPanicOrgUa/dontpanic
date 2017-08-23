<?php

namespace AdminBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use WebBundle\Entity\Reward;

/**
 * Class CustomerController
 * @package AdminBundle\Controller
 * @Security("has_role('ROLE_ADMIN')")
 */
class RewardsController extends Controller
{
    /**
     * @Route("/rewards", name="admin_rewards_list")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $rewards = $em->getRepository('WebBundle:Reward')->findAllWithGameAndCustomer();

        $paginator  = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $rewards,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('knp_paginator.page_range'))
        );

        return $this->render('AdminBundle:Reward:list.html.twig', [
            'rewards' => $result
        ]);
    }

    /**
     * @Route("/rewards/{id}/is_paid", name="admin_reward_paid")
     * @param Request $request
     * @param Reward $reward
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function isPaidAction(Request $request, Reward $reward)
    {
        $reward->setIsPaid(!$reward->getIsPaid());
        $status = $reward->getIsPaid() ? 'paid' : 'not paid';
        $em = $this->getDoctrine()->getManager();
        $em->persist($reward);
        $em->flush();
        $this->addFlash('success', sprintf('Reward is marked as %s.', $status));
        return $this->redirect($request->headers->get('referer'));
    }
}
