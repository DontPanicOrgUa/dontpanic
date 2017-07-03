<?php

namespace AdminBundle\Service;


use DateTime;
use DateTimeZone;
use WebBundle\Entity\Game;
use WebBundle\Entity\Room;

class ScheduleBuilder
{
    private $room;

    private $timeZone;

    const DATE_FORMAT = 'd-m-Y';
    const TIME_FORMAT = 'H:i';
    const DATETIME_FORMAT = 'd-m-Y H:i';

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
    public function collectByTime()
    {
        $times = [];
        foreach ($this->room->getBlanks() as $blank) {
            $games = [];
            $strTime = $blank->getTime()->format(self::TIME_FORMAT);
            for (
                $date = new DateTime('now', $this->timeZone);
                $date < new DateTime('+13 days', $this->timeZone);
                $date->modify('+1 days')
            ) {

                $strDate = $date->format(self::DATE_FORMAT);
                $prices = $blank->getPricesByDayOfWeek(strtolower($date->format('l')));
                $dateTime = new DateTime($strDate . ' ' . $strTime, $this->timeZone);
                $busy = $this->isBusy($dateTime);

                $games[] = [
                    'date' => $strDate,
                    'prices' => $prices,
                    'minPrice' => $prices->first(),
                    'busy' => $busy
                ];

            }
            $times[] = [
                'time' => $strTime,
                'games' => $games
            ];
        }
        return $times;
    }

    /**
     * @param DateTime $dateTime
     * @return bool
     */
    private function isExpired(DateTime $dateTime)
    {
        $now = new DateTime('now', $this->timeZone);
        return $now > $dateTime;
    }

    /**
     * @param DateTime $dateTime
     * @return bool|Game
     */
    public function findGameByDateTime(DateTime $dateTime)
    {
        /** @var Game $game */
        foreach ($this->room->getGames() as $game) {
            if ($game->getDatetime()->format(self::DATETIME_FORMAT) == $dateTime->format(self::DATETIME_FORMAT)) {
                return $game;
            }
        }
        return false;
    }

    public function isBusy(DateTime $dateTime)
    {
        // is expired
        if ($this->isExpired($dateTime)) {
            return true;
        }

        // is booked
        if ($game = $this->findGameByDateTime($dateTime)) {
            return $game;
        }

        // time not busy
        return false;
    }
}