<?php

namespace AdminBundle\Service;


use WebBundle\Entity\Room;
use WebBundle\Entity\Reward;
use WebBundle\Entity\Discount;
use WebBundle\Entity\Feedback;
use WebBundle\Entity\Callback as WCallback;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MailSender
{

    use NotificationMarkup;

    private $em;

    private $locale;

    private $mailer;

    private $container;

    private $templating;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->mailer = $container->get('mailer');
        $this->templating = $container->get('templating');
        $this->locale = $container->get('request_stack')->getCurrentRequest()->getLocale();
    }

    public function sendBooked($bookingData, Room $room, Discount $discount)
    {
        if ($this->container->getParameter('email')['customerBooked']) {
            $this->customerBooked($bookingData, $room, $discount);
        }
        if ($this->container->getParameter('email')['managerBooked']) {
            $this->managerBooked($bookingData, $room, $discount);
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

    public function sendFeedback(Feedback $feedback, Room $room)
    {
        if ($this->container->getParameter('email')['customerFeedback']) {
            $this->customerFeedback($feedback, $room);
        }
        if ($this->container->getParameter('email')['managerFeedback']) {
            $this->managerFeedback($feedback, $room);
        }
    }

    public function sendCallback(WCallback $callback)
    {
        if ($this->container->getParameter('email')['customerCallback']) {
            $this->customerCallback($callback);
        }
        if ($this->container->getParameter('email')['managerCallback']) {
            $this->managerCallback($callback);
        }
    }

    private function customerBooked($bookingData, Room $room, Discount $discount)
    {
        $template = $this->em
            ->getRepository('WebBundle:Notification')
            ->findOneBy([
                'type' => 'email',
                'event' => 'booked',
                'recipient' => 'customer'
            ]);

        $title = $this->bookingMarkup($template->getTitle($this->locale), $bookingData, $room, $discount);
        $message = $this->bookingMarkup($template->getMessage($this->locale), $bookingData, $room, $discount);

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

    private function managerBooked($bookingData, Room $room, Discount $discount)
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

        $title = $this->bookingMarkup($template->getTitle($this->locale), $bookingData, $room, $discount);
        $message = $this->bookingMarkup($template->getMessage($this->locale), $bookingData, $room, $discount);

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

    private function customerReward(Reward $reward)
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

    private function managerReward(Reward $reward, Room $room)
    {
        $template = $this->em
            ->getRepository('WebBundle:Notification')
            ->findOneBy([
                'type' => 'email',
                'event' => 'reward',
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

    private function customerFeedback(Feedback $feedback, Room $room)
    {
        $template = $this->em
            ->getRepository('WebBundle:Notification')
            ->findOneBy([
                'type' => 'email',
                'event' => 'feedback',
                'recipient' => 'customer'
            ]);

        $title = $this->feedbackMarkup(
            $template->getTitle($this->locale),
            $feedback,
            $room->getTitle($this->locale)
        );
        $message = $this->feedbackMarkup(
            $template->getMessage($this->locale),
            $feedback,
            $room->getTitle($this->locale)
        );

        $swiftMessage = (new \Swift_Message($title))
            ->setFrom('info@escaperooms.at', 'EscapeRooms')
            ->setTo($feedback->getEmail())
            ->setBody(
                $this->templating->render('WebBundle:emails:booking.html.twig', [
                    'message' => $message
                ]),
                'text/html'
            );
        $this->mailer->send($swiftMessage);
    }

    private function managerFeedback(Feedback $feedback, Room $room)
    {
        $template = $this->em
            ->getRepository('WebBundle:Notification')
            ->findOneBy([
                'type' => 'email',
                'event' => 'feedback',
                'recipient' => 'customer'
            ]);

        $title = $this->feedbackMarkup(
            $template->getTitle($this->locale),
            $feedback,
            $room->getTitle($this->locale)
        );
        $message = $this->feedbackMarkup(
            $template->getMessage($this->locale),
            $feedback,
            $room->getTitle($this->locale)
        );

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

    private function customerCallback(WCallback $callback)
    {
        $template = $this->em
            ->getRepository('WebBundle:Notification')
            ->findOneBy([
                'type' => 'email',
                'event' => 'callback',
                'recipient' => 'customer'
            ]);

        $title = $this->callbackMarkup(
            $template->getTitle($this->locale),
            $callback
        );
        $message = $this->callbackMarkup(
            $template->getMessage($this->locale),
            $callback
        );

        $swiftMessage = (new \Swift_Message($title))
            ->setFrom('info@escaperooms.at', 'EscapeRooms')
            ->setTo($callback->getEmail())
            ->setBody(
                $this->templating->render('WebBundle:emails:booking.html.twig', [
                    'message' => $message
                ]),
                'text/html'
            );
        $this->mailer->send($swiftMessage);
    }

    private function managerCallback(WCallback $callback)
    {
        $template = $this->em
            ->getRepository('WebBundle:Notification')
            ->findOneBy([
                'type' => 'email',
                'event' => 'callback',
                'recipient' => 'customer'
            ]);

        $title = $this->callbackMarkup(
            $template->getTitle($this->locale),
            $callback
        );
        $message = $this->callbackMarkup(
            $template->getMessage($this->locale),
            $callback
        );

        $to = $this->container->getParameter('admin')['email'];

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