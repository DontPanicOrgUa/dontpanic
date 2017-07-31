<?php

namespace AdminBundle\Service;


use WebBundle\Entity\Room;

abstract class NotificationMarkup
{
    static public function convert($text, $bookingData, Room $room)
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
                '[room_currency]',
                '[room_title]',
                '[room_city]',
                '[room_address]',
                '[room_phone]',
                '[room_email]',
                '[liqpay_btn]'
            ],
            [
                substr($bookingData['name'], 0, 10),
                substr($bookingData['lastName'], 0, 10),
                $bookingData['email'],
                preg_replace("/[^0-9]/", '', $bookingData['phone']),
                explode(' ', $bookingData['dateTime'])[0],
                explode(' ', $bookingData['dateTime'])[1],
                $bookingData['price'],
                $room->getCurrency()->getCurrency(),
                $room->getTitle(),
                $room->getCity()->getName(),
                $room->getAddress(),
                $room->getPhone(),
                $room->getEmail(),
                $bookingData['liqPay']['button']
            ],
            $text
        );
    }

}