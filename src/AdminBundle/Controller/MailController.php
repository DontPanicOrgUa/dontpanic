<?php

namespace AdminBundle\Controller;


use WebBundle\Entity\Mail;
use WebBundle\Entity\Room;
use AdminBundle\Form\MailFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class GenreController
 * @package AdminBundle\Controller
 */
class MailController extends Controller
{
    /**
     * @Route("/rooms/{slug}/mail/edit", name="admin_mail_edit")
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
            /** @var Mail $mail */
            $mail = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($mail);
            $em->flush();
            $this->addFlash('success', 'Mail Template is saved.');
            return $this->redirectToRoute('admin_mail_edit', [
                'slug' => $room->getSlug()
            ]);
        }

        return $this->render('AdminBundle:Mail:edit.html.twig', [
            'room' => $room,
            'mailForm' => $form->createView()
        ]);
    }
}
