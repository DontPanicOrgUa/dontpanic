<?php

namespace AdminBundle\Service;


use WebBundle\Entity\Room;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MailSender
{

    use NotificationMarkup;

    private $container;

    private $em;

    private $mailer;

    private $templating;

    private $locale;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->mailer = $container->get('mailer');
        $this->templating = $container->get('templating');
        $this->locale = $container->get('request_stack')->getCurrentRequest()->getLocale();
    }

    public function sendBookedGame($bookingData, Room $room)
    {
        if ($this->container->getParameter('email')['customerBooked']) {
            $this->sendToCustomer($bookingData, $room);
        }
        if ($this->container->getParameter('email')['managerBooked']) {
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
            ->setFrom('info@escaperooms.at', 'EscapeRooms')
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
            ->setFrom('info@escaperooms.com', 'EscapeRooms')
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