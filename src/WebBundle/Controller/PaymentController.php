<?php

namespace WebBundle\Controller;


use WebBundle\Entity\Game;
use WebBundle\Entity\Payment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PaymentController extends Controller
{
    /**
     * @Route("/payments/add", name="web_payments_add")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $data = $request->request->get('data');
        $signature = $request->request->get('signature');
        $sign = base64_encode(sha1(
            $this->getParameter('liqpay_private_key') .
            $data .
            $this->getParameter('liqpay_private_key'),
            1)
        );

        if ($sign != $signature) {
            $this->get('debug.logger')->error('invalid signature.', [
                'sign' => $sign,
                'signature' => $signature
            ]);
            return new JsonResponse([
                'status' => 'failure',
                'message' => 'invalid signature'
            ],400);
        }

        $jsonData = base64_decode($data);
        $objectData = json_decode($jsonData);

        $em = $this->getDoctrine()->getManager();
        $bill = $em->getRepository('WebBundle:Bill')
            ->findOneBy(['orderId' => $objectData->order_id]);

        if (!$bill) {
            $this->get('debug.logger')
                ->error(sprintf('bill with order_id: %s not found.', $objectData->order_id));
            return new JsonResponse([
                'status' => 'failure',
                'message' => 'order_id not found'
            ],404);
        }

        try {
            if ($objectData->status == 'success') {
                /** @var Game $game */
                $game = $bill->getGame();
                $game->setIsPaid(true);
                $em->persist($game);
            }

            $payment = new Payment();
            $payment->setBill($bill);
            $payment->setData($jsonData);
            $payment->setAmount($objectData->amount);
            $payment->setStatus($objectData->status);

            $em->persist($payment);
            $em->flush();
        } catch (\Exception $e) {
            mail('mp091689@gmail.com', 'ESCAPEROOMS PAYMENT FAIL', $e->getMessage());
            $this->get('debug.logger')
                ->error(sprintf('payment fail was caught'), $e);
            return new JsonResponse([
                'status' => 'failure',
                'message' => 'server error'
            ],500);
        }

        try {
            $this->get('mail_sender')->sendPayment($payment);
        } catch (\Exception $e) {
            $this->get('debug.logger')->error('mail_sender error', [$e->getMessage()]);
        }

        return new JsonResponse([
            'status' => 'success',
            'message' => 'callback accepted'
        ],201);
    }
}