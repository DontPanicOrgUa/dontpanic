<?php

namespace AdminBundle\Service;


use DateTime;
use DateTimeZone;
use WebBundle\Entity\Room;

class ScheduleBuilder
{
    private $room;

    private $timeZone;

    /**
     * ScheduleBuilder constructor.
     * @param Room $room
     */
    public function __construct(Room $room)
    {
        $this->room = $room;
        $this->timeZone = new DateTimeZone($room->getTimezone());
    }

    /**
     * @return array
     */
    public function getSchedule()
    {
        $games = [];
        for (
            $date = new DateTime('now', $this->timeZone);
            $date < new DateTime('+13 days', $this->timeZone);
            $date->modify('+1 days')
        ) {
            $dayOfWeek = strtolower($date->format('l'));
            $blanks = $this->room->getBlanks();
            foreach ($blanks as $blank) {
                $dateTime = new DateTime($date->format('d-m-Y') . ' ' . $blank->getTime()->format('H:i'), $this->timeZone);
                $prices = $blank->getPricesByDayOfWeek($dayOfWeek);
                $games[] = [
                    'dateTime' => $dateTime,
                    'minPrice' => $this->getMinPrice($prices),
                    'prices' => $prices,
                    'busy' => $this->isExpired($dateTime)
                ];
            }
        }
        return $games;
    }

    /**
     * @return array
     */
    public function collectByDate()
    {
        $dates = [];
        for (
            $date = new DateTime('now', $this->timeZone);
            $date < new DateTime('+13 days', $this->timeZone);
            $date->modify('+1 days')
        ) {
            $dayOfWeek = strtolower($date->format('l'));
            $blanks = $this->room->getBlanks();
            $games = [];
            foreach ($blanks as $blank) {
                $dateTime = new DateTime($date->format('d-m-Y') . ' ' . $blank->getTime()->format('H:i'), $this->timeZone);
                $prices = $blank->getPricesByDayOfWeek($dayOfWeek);
                $games[] = [
                    'time' => $blank->getTime()->format('H:i'),
                    'minPrice' => $this->getMinPrice($prices),
                    'prices' => $prices,
                    'busy' => $this->isExpired($dateTime)
                ];
            }
            $dates[] = [
                'date' => $date->format('d-m-Y'),
                'games' => $games
            ];
        }
        return $dates;
    }

    /**
     * @return array
     */
    public function collectByTime()
    {
        $times = [];
        foreach ($this->room->getBlanks() as $blank) {
            $games = [];
            for (
                $date = new DateTime('now', $this->timeZone);
                $date < new DateTime('+13 days', $this->timeZone);
                $date->modify('+1 days')
            ) {
                $dayOfWeek = strtolower($date->format('l'));
                $dateTime = new DateTime($date->format('d-m-Y') . ' ' . $blank->getTime()->format('H:i'), $this->timeZone);
                $prices = $blank->getPricesByDayOfWeek($dayOfWeek);
                $games[] = [
                    'date' => $date->format('d-m-Y'),
                    'minPrice' => $this->getMinPrice($prices),
                    'prices' => $prices,
                    'busy' => $this->isExpired($dateTime)
                ];
            }
            $times[] = [
                'time' => $blank->getTime()->format('H:i'),
                'games' => $games
            ];
        }
        return $times;
    }

    /**
     * @param DateTime $date
     * @return bool
     */
    private function isExpired(DateTime $date)
    {
        $now = new DateTime('now', $this->timeZone);
        return $now > $date;
    }

    /**
     * @param $prices
     * @return int|null
     */
    private function getMinPrice($prices)
    {
        $minPrice = 999999;
        if ($prices) {
            foreach ($prices as $price) {
                $minPrice = $price->getPrice() < $minPrice ? $price->getPrice() : $minPrice;
            }
            return $minPrice == 999999 ? null : $minPrice;
        }
        return null;
    }

}