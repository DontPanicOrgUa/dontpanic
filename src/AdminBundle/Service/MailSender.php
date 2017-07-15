<?php

namespace AdminBundle\Service;


use Swift_Mailer;
use WebBundle\Entity\Room;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\TwigBundle\TwigEngine;

class MailSender
{
    private $em;

    private $mailer;

    private $templating;

    public function __construct(Swift_Mailer $mailer, TwigEngine $templating, EntityManager $em)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function sendBookedGame($bookingData, Room $room)
    {
        $this->sendToCustomer($bookingData, $room);
        $this->sendToManager($bookingData, $room);
    }

    public function sendToCustomer($bookingData, Room $room)
    {
        $template = $this->em
            ->getRepository('WebBundle:Notification')
            ->findOneBy([
                'type' => 'email',
                'event' => 'booked',
                'recipient' => 'customer'
            ]);

        $title = NotificationMarkup::convert($template->getTitle(), $bookingData, $room);
        $message = NotificationMarkup::convert($template->getMessage(), $bookingData, $room);

        $swiftMessage = (new \Swift_Message($title))
            ->setFrom('dontpanic@gmail.com', 'Don\'t Panic')
            ->setTo($bookingData['email'])
            ->setBody(
                $this->templating->render('WebBundle:emails:booking.html.twig', [
                    'message' => $message
                ]),
                'text/html'
            );
        $this->mailer->send($swiftMessage);
    }

    public function sendToManager($bookingData, Room $room)
    {
        $template = $this->em
            ->getRepository('WebBundle:Notification')
            ->findOneBy([
                'type' => 'email',
                'event' => 'booked',
                'recipient' => 'manager'
            ]);
        $to = [];
        foreach ($room->getRoomManagers() as $manager) {
            $to[] = $manager->getEmail();
        }

        $title = NotificationMarkup::convert($template->getTitle(), $bookingData, $room);
        $message = NotificationMarkup::convert($template->getMessage(), $bookingData, $room);

        $swiftMessage = (new \Swift_Message($title))
            ->setFrom('dontpanic@gmail.com', 'Don\'t Panic')
            ->setTo($to)
            ->setBody(
                $this->templating->render('AdminBundle:emails:booking.html.twig', [
                    'message' => $message,
                ]),
                'text/html'
            );
        $this->mailer->send($swiftMessage);
    }

}