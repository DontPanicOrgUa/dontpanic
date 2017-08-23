<?php

namespace AdminBundle\Service;


use WebBundle\Entity\Room;
use WebBundle\Entity\Reward;

trait NotificationMarkup
{

    protected function bookingMarkup($text, $bookingData, Room $room)
    {
        return str_replace(
            [
                '[customer_name]',
                '[customer_last_name]',
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
                $room->getTitle($this->locale),
                $room->getCity()->getName($this->locale),
                $room->getAddress($this->locale),
                $room->getPhone(),
                $room->getEmail(),
                $bookingData['liqPay']['button']
            ],
            $text
        );
    }

    protected function rewardMarkup($text, Reward $reward)
    {
        return str_replace(
            [
                '[customer_name]',
                '[customer_last_name]',
                '[customer_email]',
                '[customer_phone]',
                '[reward_id]',
                '[reward_amount]',
                '[reward_currency]',
            ],
            [
                $reward->getCustomer()->getName(),
                $reward->getCustomer()->getLastName(),
                $reward->getCustomer()->getEmail(),
                $reward->getCustomer()->getPhone(),
                $reward->getId(),
                $reward->getAmount(),
                $reward->getCurrency()->getCurrency(),
            ],
            $text
        );
    }

}