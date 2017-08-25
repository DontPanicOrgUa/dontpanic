<?php

namespace AdminBundle\Controller;


use Symfony\Component\Yaml\Yaml;
use AdminBundle\Form\ParamFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class ParamController
 * @package AdminBundle\ParamController
 * @Security("has_role('ROLE_ADMIN')")
 */
class ParamController extends Controller
{

    /**
     * @Route("/params/edit", name="admin_params_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request)
    {
        $form = $this->createForm(ParamFormType::class);
        $form->setData($this->getParams());
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->setParams($request->request->all()['param_form']);
            $this->addFlash('success', 'Page is edited.');
            return $this->render('AdminBundle:Params:edit.html.twig', [
                'form' => $form->createView()
            ]);
        }
        return $this->render('AdminBundle:Params:edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function getParams()
    {
        $params = Yaml::parse(file_get_contents($this->get('kernel')->getRootDir() . '/config/extra.yml'));
        return [
            'locale' => $params['parameters']['locale'],
            'adminEmail' => $params['parameters']['admin']['email'],
            'liqpaySandbox' => $params['parameters']['liqpay']['sandbox'],
            'smsCustomerBooked' => $params['parameters']['sms']['customerBooked'],
            'smsCustomerRemind' => $params['parameters']['sms']['customerRemind'],
            'smsManagerBooked' => $params['parameters']['sms']['managerBooked'],
            'smsManagerRemind' => $params['parameters']['sms']['managerRemind'],
            'emailCustomerBooked' => $params['parameters']['email']['customerBooked'],
            'emailCustomerFeedback' => $params['parameters']['email']['customerFeedback'],
            'emailCustomerCallback' => $params['parameters']['email']['customerCallback'],
            'emailCustomerReward' => $params['parameters']['email']['customerReward'],
            'emailManagerBooked' => $params['parameters']['email']['managerBooked'],
            'emailManagerFeedback' => $params['parameters']['email']['managerFeedback'],
            'emailManagerCallback' => $params['parameters']['email']['managerCallback'],
            'emailManagerReward' => $params['parameters']['email']['managerReward'],
            'emailManagerPayment' => $params['parameters']['email']['managerPayment'],
            'discountDiscount' => $params['parameters']['discount']['discount'],
            'discountReward' => $params['parameters']['discount']['reward'],
        ];
    }

    private function setParams($params)
    {
        $parameters = [
            'parameters' => [
                'locale' => $params['locale'],
                'admin' => [
                    'email' => $params['adminEmail']
                ],
                'liqpay' => [
                    'sandbox' => $params['liqpaySandbox'] ? true : false
                ],
                'sms' => [
                    'customerBooked' => $params['smsCustomerBooked'] ? true : false,
                    'customerRemind' => $params['smsCustomerRemind'] ? true : false,
                    'managerBooked' => $params['smsManagerBooked'] ? true : false,
                    'managerRemind' => $params['smsManagerRemind'] ? true : false
                ],
                'email' => [
                    'customerBooked' => $params['emailCustomerBooked'] ? true : false,
                    'customerFeedback' => $params['emailCustomerFeedback'] ? true : false,
                    'customerCallback' => $params['emailCustomerCallback'] ? true : false,
                    'customerReward' => $params['emailCustomerReward'] ? true : false,
                    'managerBooked' => $params['emailManagerBooked'] ? true : false,
                    'managerFeedback' => $params['emailManagerFeedback'] ? true : false,
                    'managerCallback' => $params['emailManagerCallback'] ? true : false,
                    'managerReward' => $params['emailManagerReward'] ? true : false,
                    'managerPayment' => $params['emailManagerPayment'] ? true : false,
                ],
                'discount' => [
                    'discount' => $params['discountDiscount'],
                    'reward' => $params['discountReward']
                ]
            ]
        ];
        $yaml = Yaml::dump($parameters, 3);
        return file_put_contents($this->get('kernel')->getRootDir() . '/config/extra.yml', $yaml);
    }
}