<?php

namespace WebBundle\Controller;


use WebBundle\Entity\Bill;
use WebBundle\Entity\Payment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PaymentController extends Controller
{
    /**
     * @Route("/bill/{id}/payment/add")
     * @param Bill $bill
     * @param Request $request
     * @return bool
     */
    public function addAction(Bill $bill, Request $request)
    {
        $data = $request->request->get('data');
        $signature = $request->request->get('signature');
        $sign = base64_encode(sha1(
            $this->getParameter('liqpay_private_key') .
            $data .
            $this->getParameter('liqpay_private_key')
            , 1));
        if ($sign != $signature) {
            return false;
        }

        $jsonData = base64_decode($data);
        $arrayData = json_decode($jsonData);

        $payment = new Payment();
        $payment->setBill($bill);
        $payment->setData($jsonData);
        $payment->setOrderId($arrayData['order_id']);
        $payment->setAmount($arrayData['amount']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($payment);
        $em->flush();

        return true;
    }
}