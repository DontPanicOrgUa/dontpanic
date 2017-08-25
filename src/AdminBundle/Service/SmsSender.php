<?php

namespace AdminBundle\Service;


use WebBundle\Entity\Discount;
use WebBundle\Entity\Room;
use Mp091689\TurboSms\TurboSms;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SmsSender
{
    use NotificationMarkup;

    private $em;

    private $sender;

    private $locale;

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->sender = new TurboSms(
            $container->getParameter('turbosms_host'),
            $container->getParameter('turbosms_name'),
            $container->getParameter('turbosms_user'),
            $container->getParameter('turbosms_pass')
        );
        $this->locale = $container->get('request_stack')->getCurrentRequest()->getLocale();
    }

    public function send($bookingData, Room $room, Discount $discount)
    {
        if ($this->container->getParameter('sms')['customerBooked']) {
            $this->sendToCustomer($bookingData, $room, $discount);
        }
        if ($this->container->getParameter('sms')['customerRemind']) {
            $this->sendRemindToCustomer($bookingData, $room, $discount);
        }
        if ($this->container->getParameter('sms')['managerBooked']) {
            $this->sendToManagers($bookingData, $room, $discount);
        }
        if ($this->container->getParameter('sms')['managerRemind']) {
            $this->sendRemindToManagers($bookingData, $room, $discount);
        }
    }

    public function sendToCustomer($bookingData, Room $room, Discount $discount)
    {
        $template = $this->getTemplate('sms', 'booked', 'customer');
        $message = $this->bookingMarkup($template->getMessage($this->locale), $bookingData, $room, $discount);
        return $this->sender->send($bookingData['phone'], $message);
    }

    public function sendToManagers($bookingData, Room $room, Discount $discount)
    {
        $template = $this->getTemplate('sms', 'booked', 'manager');
        $message = $this->bookingMarkup($template->getMessage($this->locale), $bookingData, $room, $discount);
        foreach ($room->getRoomManagers() as $manager) {
            $this->sender->send($manager->getPhone(), $message);
        }
    }

    public function sendRemindToCustomer($bookingData, Room $room, Discount $discount)
    {
        $template = $this->getTemplate('sms', 'booked', 'customer');
        $message = $this->bookingMarkup($template->getMessage($this->locale), $bookingData, $room, $discount);
        $remindTime = $this->getRemindTime($bookingData['dateTime'], $room);
        return $this->sender->send($bookingData['phone'], $message, 'Msg', $remindTime);
    }

    public function sendRemindToManagers($bookingData, Room $room, Discount $discount)
    {
        $template = $this->getTemplate('sms', 'booked', 'manager');
        $message = $this->bookingMarkup($template->getMessage($this->locale), $bookingData, $room, $discount);
        $remindTime = $this->getRemindTime($bookingData['dateTime'], $room);
        foreach ($room->getRoomManagers() as $manager) {
            $this->sender->send($manager->getPhone(), $message, 'Msg', $remindTime);
        }
    }

    private function getTemplate($type, $event, $recipient)
    {
        return $this->em
            ->getRepository('WebBundle:Notification')
            ->findOneBy([
                'type' => $type,
                'event' => $event,
                'recipient' => $recipient
            ]);
    }

    private function getRemindTime($dateTime, Room $room)
    {
        $dt = new \DateTime($dateTime, new \DateTimeZone($room->getCity()->getTimezone()));
        return $dt
            ->setTimezone(new \DateTimeZone('Europe/Kiev'))
            ->modify('- 2 hours')
            ->format('Y-m-d H:i');
    }
}