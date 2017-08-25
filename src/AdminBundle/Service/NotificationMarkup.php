<?php

namespace AdminBundle\Service;


use WebBundle\Entity\Bill;
use WebBundle\Entity\Customer;
use WebBundle\Entity\Game;
use WebBundle\Entity\Payment;
use WebBundle\Entity\Room;
use WebBundle\Entity\Reward;
use WebBundle\Entity\Discount;
use WebBundle\Entity\Feedback;
use WebBundle\Entity\Callback as WCallback;

trait NotificationMarkup
{

    protected function bookingMarkup($text, $bookingData, Room $room, Discount $discount)
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
                '[room_title]',
                '[room_city]',
                '[room_address]',
                '[room_phone]',
                '[room_email]',
                '[liqpay_btn]',
                '[new_discount_code]',
                '[new_discount_percent]'
            ],
            [
                substr($bookingData['name'], 0, 10),
                substr($bookingData['lastName'], 0, 10),
                $bookingData['email'],
                preg_replace("/[^0-9]/", '', $bookingData['phone']),
                explode(' ', $bookingData['dateTime'])[0],
                explode(' ', $bookingData['dateTime'])[1],
                $bookingData['price'] . ' ' . $room->getCurrency()->getCurrency(),
                $room->getTitle($this->locale),
                $room->getCity()->getName($this->locale),
                $room->getAddress($this->locale),
                $room->getPhone(),
                $room->getEmail(),
                $bookingData['liqPay']['button'],
                $discount->getCode(),
                $discount->getDiscount()
            ],
            $text
        );
    }

    protected function rewardMarkup($text, Reward $reward)
    {
        return str_replace(
            [
                '[reward_id]',
                '[reward_amount]',
                '[customer_name]',
                '[customer_last_name]',
                '[customer_email]',
                '[customer_phone]',
                '[customer_percentage]',
                '[game_time]',
                '[room_title]',
            ],
            [
                $reward->getId(),
                $reward->getAmount() . ' ' . $reward->getCurrency()->getCurrency(),
                $reward->getCustomer()->getName(),
                $reward->getCustomer()->getLastName(),
                $reward->getCustomer()->getEmail(),
                $reward->getCustomer()->getPhone(),
                $reward->getCustomer()->getPercentage(),
                $reward->getGame()->getDatetime()->format('d.m.Y H:i'),
                $reward->getGame()->getRoom()->getTitleEn(),
            ],
            $text
        );
    }

    protected function callbackMarkup($text, WCallback $callback)
    {
        return str_replace(
            [
                '[customer_name]',
                '[customer_email]',
                '[customer_phone]',
                '[customer_comment]',
            ],
            [
                $callback->getName(),
                $callback->getEmail(),
                $callback->getPhone(),
                $callback->getComment()
            ],
            $text
        );
    }

    protected function feedbackMarkup($text, Feedback $feedback, $title)
    {
        return str_replace(
            [
                '[customer_name]',
                '[customer_email]',
                '[customer_phone]',
                '[customer_comment]',
                '[game_result]',
                '[game_atmosphere]',
                '[game_story]',
                '[game_service]',
                '[room_title]'
            ],
            [
                $feedback->getName(),
                $feedback->getEmail(),
                $feedback->getPhone(),
                $feedback->getComment(),
                $feedback->getTime(),
                $feedback->getAtmosphere(),
                $feedback->getStory(),
                $feedback->getService(),
                $title
            ],
            $text
        );
    }

    protected function paymentMarkup($text, Payment $payment)
    {
        /** @var Bill $bill */
        $bill = $payment->getBill();
        /** @var Game $game */
        $game = $bill->getGame();
        /** @var Room $room */
        $room = $game->getRoom();
        /** @var Customer $customer */
        $customer = $game->getCustomer();

        return str_replace(
            [
                '[payment_status]',
                '[payment_amount]',
                '[game_time]',
                '[room_title]',
                '[customer_name]',
                '[customer_last_name]',
                '[customer_email]',
                '[customer_phone]',
            ],
            [
                $payment->getStatus(),
                $payment->getAmount() . ' ' . $room->getCurrency()->getCurrency(),
                $game->getDatetime()->format('d.m.Y H:i'),
                $room->getTitleEn(),
                $customer->getName(),
                $customer->getLastName(),
                $customer->getEmail(),
                $customer->getPhone()
            ],
            $text
        );
    }

}