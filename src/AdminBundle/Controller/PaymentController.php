<?php

namespace AdminBundle\Controller;


use WebBundle\Entity\Game;
use WebBundle\Entity\Payment;
use AdminBundle\Form\PaymentFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class PaymentController
 * @package AdminBundle\Controller
 * @Security("has_role('ROLE_ADMIN')")
 */
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
        $payments = $em->getRepository('WebBundle:Payment')->findAll();

        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $payments,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('knp_paginator.page_range'))
        );

        return $this->render('AdminBundle:Payment:list.html.twig', [
            'payments' => $result
        ]);
    }

    /**
     * @Route("rooms/{slug}/games/{id}/payments/add", name="admin_payment_add")
     * @param Request $request
     * @param $slug
     * @param Game $game
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request, $slug, Game $game)
    {
        $form = $this->createForm(PaymentFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Payment $payment */
            $payment = $form->getData();
            $payment->setBill($game->getBills()[0]);
            $payment->setStatus('success');
            $payment->setData(json_encode([
                'order_id' => 'cash',
                'amount' => $form->get('amount')->getData(),
                'currency' => $game->getRoom()->getCurrency()->getCurrency(),
                'status' => 'success',
                'comment' => $form->get('data')->getData()
            ]));
            $game->setIsPaid(true);
            $em = $this->getDoctrine()->getManager();

            $em->persist($payment);
            $em->persist($game);
            $em->flush();
            $this->addFlash('success', 'Payment is created.');
            return $this->redirectToRoute('admin_games_show', [
                'slug' => $slug,
                'id' => $game->getId()
            ]);
        }

        return $this->render('AdminBundle:Payment:add.html.twig', [
            'paymentForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/payments/{id}/delete", name="admin_payment_delete")
     * @param Request $request
     * @param Payment $payment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, Payment $payment)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($payment);
        /** @var Game $game */
        $game = $payment->getBill()->getGame();
        $game->setIsPaid(false);
        $em->persist($game);
        $em->flush();
        $this->addFlash('success', 'Payment is deleted.');
        return $this->redirect($request->headers->get('referer'));
    }
}
