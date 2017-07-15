<?php

namespace AdminBundle\Controller;


use WebBundle\Entity\Notification;
use WebBundle\Entity\Room;
use AdminBundle\Form\MailFormType;
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
     * @Route("/rooms/{slug}/notification/edit", name="admin_notification_edit")
     * @param Request $request
     * @param Room $room
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Room $room)
    {
        $mail = $room->getMailTemplate();
        $form = $this->createForm(MailFormType::class, $mail);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $notification = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($notification);
            $em->flush();
            $this->addFlash('success', 'Template is saved.');
            return $this->redirectToRoute('admin_notification_edit', [
                'slug' => $room->getSlug()
            ]);
        }

        return $this->render('AdminBundle:Notification:edit.html.twig', [
            'room' => $room,
            'form' => $form->createView()
        ]);
    }
}
