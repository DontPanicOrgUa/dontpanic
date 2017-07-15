<?php

namespace AdminBundle\Service;


use WebBundle\Entity\Room;

abstract class NotificationMarkup
{
    static public function convert($template, $bookingData, Room $room)
    {
        return str_replace(
            [
                '[customer_name]',
                '[customer_second_name]',
                '[customer_email]',
                '[customer_phone]',
                '[game_date]',
                '[game_time]',
                '[game_price]',
                '[room_title]',
                '[room_city]',
                '[room_address]',
                '[room_phone]',
                '[room_email]'
            ],
            [
                $bookingData['name'],
                $bookingData['secondName'],
                $bookingData['email'],
                $bookingData['phone'],
                explode(' ', $bookingData['dateTime'])[0],
                explode(' ', $bookingData['dateTime'])[1],
                $bookingData['price'] . ' ' . $room->getCurrency()->getCurrency(),
                $room->getTitle(),
                $room->getCity()->getName(),
                $room->getAddress(),
                $room->getPhone(),
                $room->getEmail()
            ],
            $template
        );
    }

}