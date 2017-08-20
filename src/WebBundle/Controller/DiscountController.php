<?php

namespace WebBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DiscountController extends Controller
{
    /**
     * @Route("/discount", name="web_discount_show")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function showAction(Request $request)
    {
        $code = trim($request->request->get('discount'));
        $em = $this->getDoctrine()->getManager();
        $discount = $em
            ->getRepository('WebBundle:Discount')
            ->findOneByCode($code);
        if ($discount) {
            $httpCode = 200;
            return new JsonResponse($discount, $httpCode);
        }
        $httpCode = 404;
        return new JsonResponse([
            'code' => $httpCode,
            'message' => 'Discount not found.'
        ], $httpCode);
    }
}