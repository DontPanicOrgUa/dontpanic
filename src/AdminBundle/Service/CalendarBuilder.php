<?php

namespace AdminBundle\Service;


use DateTime;
use DateTimeZone;
use WebBundle\Entity\Room;

class CalendarBuilder
{
    private $room;

    private $timeZone;

    public function __construct(Room $room)
    {
        $this->room = $room;
        $this->timeZone = new DateTimeZone($room->getTimezone());
    }

    /**
     * Time will takes first place in hierarchy
     * First will be shown time of game,
     * every time contains two weeks of dates,
     * every date contains all prices of game
     * @return array
     */
    public function getTimeDrivenCalendar() {
        $date = new DateTime();
        $date->setTimezone($this->timeZone);
        $times = [];
        foreach ($this->room->getBlanks() as $blank) {
            $prices = [];
            foreach ($blank->getPrices() as $price) {
                $prices[$price->getDayOfWeek()][] = [
                    'dayOfWeek' => $price->getDayOfWeek(),
                    'players' => $price->getPlayers(),
                    'price' => $price->getPrice()
                ];
            }
            $games = [];
            for ($i = 0; $i < 14; $i++) {
                if ($i > 0) {
                    $date->modify('+1 day');
                }
                $games[] = [
                    'date' => $date->format('d-m-Y'),
                    'prices' => $prices[strtolower($date->format('l'))],
                    'busy' => $this->gameDateTimeBusy('dateTimeMustBe')
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
     * Response contain array of dates and games,
     * every game contains objects with time, price, and busy state
     * @return array
     */
    public function getDayDrivenCalendar() {
        $date = new DateTime();
        $date->setTimezone($this->timeZone);
        $days = [];
        for ($i = 0; $i < 14; $i++) {
            if ($i > 0) {
                $date->modify('+1 day');
            }
            $games = [];
            foreach ($this->room->getBlanks() as $blank) {
                $prices = [];
                foreach ($blank->getPrices() as $price) {
                    $prices[$price->getDayOfWeek()][] = $price;
                }
                $games[] = [
                    'timeGame' => $blank->getTime()->format('H:i:s'),
                    'cost' => $prices[strtolower($date->format('l'))][0]->getPrice(),
                    'busy' => $this->gameDateTimeBusy('dateTimeMustBe')
                ];
            }
            $days[] = [
                'date' => $date->format('d-m-Y'),
                'games' => $games
            ];
        }
        return $days;
    }

    public function getCalendarHeaders() {
        $date = new DateTime();
        $date->setTimezone($this->timeZone);
        $headers = [];
        for ($i = 0; $i < 7; $i++) {
            if ($i > 0) {
                $date->modify('+1 day');
            }
            $headers[] = $date->format('l');
        }
        return $headers;
    }

    public function gameDateTimeBusy($datetime)
    {
        return false;
    }
}