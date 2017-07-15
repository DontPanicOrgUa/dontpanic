<?php

namespace AdminBundle\Service;


use Swift_Mailer;
use WebBundle\Entity\Notification;
use WebBundle\Entity\Room;
use Symfony\Bundle\TwigBundle\TwigEngine;

class MailSender
{
    private $mailer;

    private $templating;

    public function __construct(Swift_Mailer $mailer, TwigEngine $templating)
    {
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
        $mailTemplate = '';
        $message = NotificationMarkup::convert($mailTemplate, $bookingData, $room);
        $swiftMessage = (new \Swift_Message($mailTemplate->getTitle()))
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
        $to = [];
        foreach ($room->getRoomManagers() as $manager) {
            $to[] = $manager->getEmail();
        }
        $swiftMessage = (new \Swift_Message($bookingData['dateTime'] . ' ' . $room->getTitle()))
            ->setFrom('dontpanic@gmail.com', 'Don\'t Panic')
            ->setTo($to)
            ->setBody(
                $this->templating->render('AdminBundle:emails:booking.html.twig', [
                    'bookingData' => $bookingData,
                    'room' => $room
                ]),
                'text/html'
            );
        $this->mailer->send($swiftMessage);
    }

}