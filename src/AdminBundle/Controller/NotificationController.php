<?php

namespace AdminBundle\Controller;


use WebBundle\Entity\Notification;
use WebBundle\Entity\Room;
use AdminBundle\Form\NotificationFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class GenreController
 * @package AdminBundle\Controller
 */
class NotificationController extends Controller
{

    /**
     * @Route("/notifications", name="admin_notifications_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $notifications = $em->getRepository('WebBundle:Notification')->findAll();

        return $this->render('AdminBundle:Notification:list.html.twig', [
            'notifications' => $notifications
        ]);
    }

    /**
     * @Route("/notifications/{id}/edit", name="admin_notifications_edit")
     * @param Request $request
     * @param Notification $notification
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Notification $notification)
    {
        $form = $this->createForm(NotificationFormType::class, $notification);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $notification = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($notification);
            $em->flush();
            $this->addFlash('success', 'Notification is edited.');
            return $this->redirectToRoute('admin_notifications_list');
        }

        $view = 'AdminBundle:Notification:edit_mail.html.twig';
        if ($notification->getType() == 'sms') {
            $view = 'AdminBundle:Notification:edit_sms.html.twig';
        }

        return $this->render($view, [
            'form' => $form->createView()
        ]);
    }
}
