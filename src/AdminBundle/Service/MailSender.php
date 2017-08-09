<?php

namespace AdminBundle\Service;


use Swift_Mailer;
use WebBundle\Entity\Room;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpFoundation\RequestStack;

class MailSender
{

    use NotificationMarkup;

    private $em;

    private $mailer;

    private $templating;

    private $locale;

    public function __construct(Swift_Mailer $mailer, TwigEngine $templating, EntityManager $em, RequestStack $request)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->locale = $request->getCurrentRequest()->getLocale();
    }

    public function sendBookedGame($bookingData, Room $room)
    {
        if ($room->getClientMailNotification()) {
            $this->sendToCustomer($bookingData, $room);
        }
        if ($room->getManagerMailNotification()) {
            $this->sendToManager($bookingData, $room);
        }
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

        $title = $this->markup($template->getTitle($this->locale), $bookingData, $room);
        $message = $this->markup($template->getMessage($this->locale), $bookingData, $room);

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

        $title = $this->markup($template->getTitle($this->locale), $bookingData, $room);
        $message = $this->markup($template->getMessage($this->locale), $bookingData, $room);

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