<?php

namespace AdminBundle\Service;


use WebBundle\Entity\Room;
use Doctrine\ORM\EntityManager;
use Mp091689\TurboSms\TurboSms;

class SmsSender
{
    private $em;

    private $sender;

    public function __construct($host, $db_name, $user, $password, EntityManager $em)
    {
        $this->em = $em;
        $this->sender = new TurboSms($host, $db_name, $user, $password);
    }

    public function send($bookingData, Room $room)
    {
        $this->sendToCustomer($bookingData, $room);
        $this->sendRemindToCustomer($bookingData, $room);
        $this->sendToManagers($bookingData, $room);
        $this->sendRemindToManagers($bookingData, $room);
    }

    public function sendToCustomer($bookingData, Room $room)
    {
        $template = $this->getTemplate('sms', 'booked', 'customer');
        $message = NotificationMarkup::convert($template->getMessage(), $bookingData, $room);
        return $this->sender->send($bookingData['phone'], $message);
    }

    public function sendToManagers($bookingData, Room $room)
    {
        $template = $this->getTemplate('sms', 'booked', 'manager');
        $message = NotificationMarkup::convert($template->getMessage(), $bookingData, $room);
        foreach ($room->getRoomManagers() as $manager) {
            $this->sender->send($manager->getPhone(), $message);
        }
    }

    public function sendRemindToCustomer($bookingData, Room $room)
    {
        $template = $this->getTemplate('sms', 'booked', 'customer');
        $message = NotificationMarkup::convert($template->getMessage(), $bookingData, $room);
        $remindTime = $this->getRemindTime($bookingData['dateTime'], $room);
        return $this->sender->send($bookingData['phone'], $message, 'Msg', $remindTime);
    }

    public function sendRemindToManagers($bookingData, Room $room)
    {
        $template = $this->getTemplate('sms', 'booked', 'customer');
        $message = NotificationMarkup::convert($template->getMessage(), $bookingData, $room);
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
        $dt = new \DateTime($dateTime, new \DateTimeZone($room->getTimezone()));
        return $dt
            ->setTimezone(new \DateTimeZone('Europe/Kiev'))
            ->modify('- 2 hours')
            ->format('Y-m-d H:i');
    }
}