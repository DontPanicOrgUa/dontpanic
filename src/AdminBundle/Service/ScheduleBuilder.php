<?php

namespace AdminBundle\Service;


use DateTime;
use DateTimeZone;
use WebBundle\Entity\Corrective;
use WebBundle\Entity\Game;
use WebBundle\Entity\Room;

class ScheduleBuilder
{
    private $room;

    private $timeZone;

    const DATE_FORMAT = 'd.m.Y';
    const TIME_FORMAT = 'H:i';
    const DATETIME_FORMAT = 'd.m.Y H:i';

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
    public function collect()
    {
        $sevenDays = [];
        for ($sd = 1; $sd <= 4; $sd++) {
            $times = [];
            foreach ($this->room->getBlanks() as $blank) {
                $games = [];
                $strTime = $blank->getTime()->format(self::TIME_FORMAT);

                for (
                    $date = new DateTime('now + ' . ($sd * 7 - 7) . ' days', $this->timeZone);
                    $date < new DateTime('now + ' . ($sd * 7 - 1) . ' days', $this->timeZone);
                    $date->modify('+1 days')
                ) {

                    $strDate = $date->format(self::DATE_FORMAT);
                    $dateTime = new DateTime($strDate . ' ' . $strTime, $this->timeZone);
                    $prices = [];
                    $pricesCollection = $blank->getPricesByDayOfWeek(strtolower($date->format('l')));
                    foreach ($pricesCollection as $price) {
                        $prices[] = [
                            'players' => $price->getPlayers(),
                            'price' => $price->getPrice()
                        ];
                    }
                    $corrective = $this->findCorrective($dateTime);
                    if ($corrective) {
                        $prices = $corrective['prices'];
                    }

                    $busy = $this->isBusy($dateTime);

                    $games[] = [
                        'date' => $strDate,
                        'prices' => $prices,
                        'corrective' => $corrective,
                        'busy' => $busy
                    ];

                }
                $times[] = [
                    'time' => $strTime,
                    'dates' => $games
                ];
            }
            $sevenDays[] = $times;
        }
        foreach ($sevenDays as $days) {
            if (empty($days)) {
                return [];
            }
        }
        return $sevenDays;
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

    private function findCorrective(DateTime $dateTime)
    {
        /** @var Corrective $corrective */
        foreach ($this->room->getCorrectives() as $corrective) {
            if ($corrective->getDatetime()->format(self::DATETIME_FORMAT) == $dateTime->format(self::DATETIME_FORMAT)) {
                return [
                    'id' => $corrective->getId(),
                    'prices' => unserialize($corrective->getData())
                ];
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