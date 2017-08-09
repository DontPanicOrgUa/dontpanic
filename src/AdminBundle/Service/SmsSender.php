<?php

namespace AdminBundle\Service;


use WebBundle\Entity\Room;
use Doctrine\ORM\EntityManager;
use Mp091689\TurboSms\TurboSms;
use Symfony\Component\HttpFoundation\RequestStack;

class SmsSender
{
    use NotificationMarkup;

    private $em;

    private $sender;

    private $locale;

    public function __construct($host, $db_name, $user, $password, EntityManager $em, RequestStack $request)
    {
        $this->em = $em;
        $this->sender = new TurboSms($host, $db_name, $user, $password);
        $this->locale = $request->getCurrentRequest()->getLocale();
    }

    public function send($bookingData, Room $room)
    {
        if ($room->getClientSmsNotification()) {
            $this->sendToCustomer($bookingData, $room);
        }
        if ($room->getClientSmsReminder()) {
            $this->sendRemindToCustomer($bookingData, $room);
        }
        if ($room->getManagerSmsNotification()) {
            $this->sendToManagers($bookingData, $room);
        }
        if ($room->getManagerSMSReminder()) {
            $this->sendRemindToManagers($bookingData, $room);
        }
    }

    public function sendToCustomer($bookingData, Room $room)
    {
        $template = $this->getTemplate('sms', 'booked', 'customer');
        $message = $this->markup($template->getMessage($this->locale), $bookingData, $room);
        return $this->sender->send($bookingData['phone'], $message);
    }

    public function sendToManagers($bookingData, Room $room)
    {
        $template = $this->getTemplate('sms', 'booked', 'manager');
        $message = $this->markup($template->getMessage($this->locale), $bookingData, $room);
        foreach ($room->getRoomManagers() as $manager) {
            $this->sender->send($manager->getPhone(), $message);
        }
    }

    public function sendRemindToCustomer($bookingData, Room $room)
    {
        $template = $this->getTemplate('sms', 'booked', 'customer');
        $message = $this->markup($template->getMessage($this->locale), $bookingData, $room);
        $remindTime = $this->getRemindTime($bookingData['dateTime'], $room);
        return $this->sender->send($bookingData['phone'], $message, 'Msg', $remindTime);
    }

    public function sendRemindToManagers($bookingData, Room $room)
    {
        $template = $this->getTemplate('sms', 'booked', 'customer');
        $message = $this->markup($template->getMessage($this->locale), $bookingData, $room);
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