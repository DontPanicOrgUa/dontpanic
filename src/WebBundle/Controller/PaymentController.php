<?php

namespace WebBundle\Controller;


use WebBundle\Entity\Payment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PaymentController extends Controller
{
    /**
     * @Route("/payment/{orderId}/add", name="web_payment_add")
     * @Method("POST")
     * @param $orderId
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction($orderId, Request $request)
    {
        $data = $request->request->get('data');
        $signature = $request->request->get('signature');
        $sign = base64_encode(sha1(
            $this->getParameter('liqpay_private_key') .
            $data .
            $this->getParameter('liqpay_private_key')
            , 1));
        mail('mp091689@gmail.com', 'testPay', json_encode([
            'data' => $data,
            'signature' => $signature,
            'sign' => $sign
        ]));
//        if ($sign != $signature) {
//            return new JsonResponse([
//                'status' => 'failure',
//                'message' => 'invalid signature'
//            ],400);
//        }

        $jsonData = base64_decode($data);
        $arrayData = json_decode($jsonData);

        $em = $this->getDoctrine()->getManager();
        $bill = $em->getRepository('WebBundle:Bill')
            ->findOneBy(['orderId' => $orderId]);

        $payment = new Payment();
        $payment->setBill($bill);
        $payment->setData($jsonData);
        $payment->setOrderId($arrayData['order_id']);
        $payment->setAmount($arrayData['amount']);

        $em->persist($payment);

        $em->flush();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'callback accepted'
        ],201);
    }
}