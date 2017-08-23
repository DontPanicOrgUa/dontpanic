<?php

namespace AdminBundle\Service;


use WebBundle\Entity\Room;
use WebBundle\Entity\Reward;
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

    public function sendBooked($bookingData, Room $room)
    {
        if ($this->container->getParameter('email')['customerBooked']) {
            $this->customerBooked($bookingData, $room);
        }
        if ($this->container->getParameter('email')['managerBooked']) {
            $this->managerBooked($bookingData, $room);
        }
    }

    public function sendReward(Reward $reward, Room $room)
    {
        if ($this->container->getParameter('email')['customerReward']) {
            $this->customerReward($reward);
        }
        if ($this->container->getParameter('email')['managerReward']) {
            $this->managerReward($reward, $room);
        }
    }

    public function customerBooked($bookingData, Room $room)
    {
        $template = $this->em
            ->getRepository('WebBundle:Notification')
            ->findOneBy([
                'type' => 'email',
                'event' => 'booked',
                'recipient' => 'customer'
            ]);

        $title = $this->bookingMarkup($template->getTitle($this->locale), $bookingData, $room);
        $message = $this->bookingMarkup($template->getMessage($this->locale), $bookingData, $room);

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

    public function managerBooked($bookingData, Room $room)
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

        $title = $this->bookingMarkup($template->getTitle($this->locale), $bookingData, $room);
        $message = $this->bookingMarkup($template->getMessage($this->locale), $bookingData, $room);

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

    public function customerReward(Reward $reward)
    {
        $template = $this->em
            ->getRepository('WebBundle:Notification')
            ->findOneBy([
                'type' => 'email',
                'event' => 'reward',
                'recipient' => 'customer'
            ]);

        $title = $this->rewardMarkup($template->getTitle($this->locale), $reward);
        $message = $this->rewardMarkup($template->getMessage($this->locale), $reward);

        $swiftMessage = (new \Swift_Message($title))
            ->setFrom('info@escaperooms.at', 'EscapeRooms')
            ->setTo($reward->getCustomer()->getEmail())
            ->setBody(
                $this->templating->render('WebBundle:emails:booking.html.twig', [
                    'message' => $message
                ]),
                'text/html'
            );
        $this->mailer->send($swiftMessage);
    }

    public function managerReward(Reward $reward, Room $room)
    {
        $template = $this->em
            ->getRepository('WebBundle:Notification')
            ->findOneBy([
                'type' => 'email',
                'event' => 'booked',
                'recipient' => 'manager'
            ]);

        $title = $this->rewardMarkup($template->getTitle($this->locale), $reward);
        $message = $this->rewardMarkup($template->getMessage($this->locale), $reward);

        $to = [];
        foreach ($room->getRoomManagers() as $manager) {
            $to[] = $manager->getEmail();
        }

        $swiftMessage = (new \Swift_Message($title))
            ->setFrom('info@escaperooms.at', 'EscapeRooms')
            ->setTo($to)
            ->setBody(
                $this->templating->render('WebBundle:emails:booking.html.twig', [
                    'message' => $message
                ]),
                'text/html'
            );
        $this->mailer->send($swiftMessage);
    }
}