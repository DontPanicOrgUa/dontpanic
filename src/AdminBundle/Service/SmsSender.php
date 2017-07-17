<?php

namespace AdminBundle\Service;



use Doctrine\ORM\EntityManager;
use Mp091689\TurboSms\TurboSms;
use WebBundle\Entity\Room;

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
        $template = $this->em
            ->getRepository('WebBundle:Notification')
            ->findOneBy([
                'type' => 'sms',
                'event' => 'booked',
                'recipient' => 'customer'
            ]);
        $message = NotificationMarkup::convert($template->getMessage(), $bookingData, $room);
        return $this->sender->send($bookingData['phone'], $message);
    }
}