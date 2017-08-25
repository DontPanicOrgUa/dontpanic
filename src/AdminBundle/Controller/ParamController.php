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
        $params = Yaml::parse(file_get_contents($this->get('kernel')->getRootDir() . '/config/extra.yml'));
        $form->setData($params['parameters']);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $params = $this->collectParams($request->request->all()['param_form']);
            $yaml = Yaml::dump($params);
            file_put_contents($this->get('kernel')->getRootDir() . '/config/extra.yml', $yaml);
            $this->addFlash('success', 'Page is edited.');
            return $this->render('AdminBundle:Params:edit.html.twig', [
                'form' => $form->createView()
            ]);
        }
        return $this->render('AdminBundle:Params:edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function collectParams($params)
    {
        unset($params['save']);
        unset($params['_token']);
        $params['liqpay_sandbox'] = (bool)$params['liqpay_sandbox'];
        $params['sms_customer_booked'] = (bool)$params['sms_customer_booked'];
        $params['sms_customer_remind'] = (bool)$params['sms_customer_remind'];
        $params['sms_manager_booked'] = (bool)$params['sms_manager_booked'];
        $params['sms_manager_remind'] = (bool)$params['sms_manager_remind'];
        $params['email_customer_booked'] = (bool)$params['email_customer_booked'];
        $params['email_customer_feedback'] = (bool)$params['email_customer_feedback'];
        $params['email_customer_callback'] = (bool)$params['email_customer_callback'];
        $params['email_customer_reward'] = (bool)$params['email_customer_reward'];
        $params['email_manager_booked'] = (bool)$params['email_manager_booked'];
        $params['email_manager_feedback'] = (bool)$params['email_manager_feedback'];
        $params['email_manager_callback'] = (bool)$params['email_manager_callback'];
        $params['email_manager_reward'] = (bool)$params['email_manager_reward'];
        $params['email_manager_payment'] = (bool)$params['email_manager_payment'];
        return ['parameters' => $params];
    }
}