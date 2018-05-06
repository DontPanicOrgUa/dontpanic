<?php
/**
 * Created by PhpStorm.
 * User: mykyta
 * Date: 4/29/18
 * Time: 10:13 AM
 */

declare(strict_types=1);

namespace RoomBundle\Service\Schedule;


use DateTime;
use DateTimeZone;
use RoomBundle\Entity\Blank;
use RoomBundle\Entity\Corrective;
use RoomBundle\Entity\Game;
use RoomBundle\Entity\Price;
use RoomBundle\Entity\Room;
use RoomBundle\Service\Schedule\Interfaces\ScheduleServiceInterface;
use RoomBundle\Service\Schedule\Interfaces\StrategyScheduleInterface;

class ScheduleService implements ScheduleServiceInterface
{
    const DATE_FORMAT = 'd.m.Y';
    const TIME_FORMAT = 'H:i';
    const DATETIME_FORMAT = 'd.m.Y H:i';

    private $scheduleLength = 28;

    private $timeZone;

    public function getSchedule(Room $room, string $scheduleClass = null): array
    {
        $this->timeZone = new DateTimeZone((string)$room->getCity()->getTimezone());

        $schedule = [];
        for ($day = 0; $day < $this->scheduleLength; $day++) {
            $daysCounter = new DateTime("now + {$day} days", $this->timeZone);
            $dateStr = $daysCounter->format(self::DATE_FORMAT);
            $games = [];
            foreach ($room->getBlanks() as $blank) {
                $timeStr = $blank->getTime()->format(self::TIME_FORMAT);
                $dateTime = new DateTime("{$dateStr} {$timeStr}", $this->timeZone);
                $booked = $this->isBooked($room, $dateTime);
                $corrective = $this->getCorrective($room, $dateTime);
                $prices = $this->getPrices($blank, $dateTime, $corrective);
                $games[] = [
                    'time' => $timeStr,
                    'booked' => $booked,
                    'corrective' => $corrective ? [
                        'id' => $corrective->getId(),
                        'prices' => unserialize($corrective->getData()),
                    ] : false,
                    'prices' => $prices,
                ];
            }
            $schedule[] = [
                'date' => $dateStr,
                'games' => $games
            ];
        }

        if (empty($scheduleClass)) {
            return $schedule;

        }
        /** @var StrategyScheduleInterface $strategySchedule */
        $strategySchedule = new $scheduleClass();
        return $strategySchedule->convert($schedule);
    }

    private function getGame(Room $room, DateTime $dateTime): ?Game
    {
        /** @var Game $game */
        foreach ($room->getGames() as $game) {
            if ($game->getDatetime()->format(self::DATETIME_FORMAT) === $dateTime->format(self::DATETIME_FORMAT)) {
                return $game;
            }
        }
        return null;
    }

    private function getCorrective(Room $room, DateTime $dateTime): ?Corrective
    {
        /** @var Corrective $corrective */
        foreach ($room->getCorrectives() as $corrective) {
            if ($corrective->getDatetime()->format(self::DATETIME_FORMAT) === $dateTime->format(self::DATETIME_FORMAT)) {
                return $corrective;
            }
        }
        return null;
    }

    private function isBooked(Room $room, DateTime $dateTime): bool
    {
        $game = $this->getGame($room, $dateTime);
        $expired = new DateTime('now', $this->timeZone) > $dateTime;
        return $expired || (bool)$game;
    }

    private function getPrices(Blank $blank, DateTime $dateTime, Corrective $corrective = null): array
    {
        if (!empty($corrective)) {
            return unserialize($corrective->getData());
        }
        $prices = [];
        $pricesCollection = $blank->getPricesByDayOfWeek(strtolower($dateTime->format('l')));
        foreach ($pricesCollection as $price) {
            /** @var Price $price */
            $prices[] = [
                'id' => $price->getId(),
                'players' => $price->getPlayers(),
                'price' => $price->getPrice()
            ];
        }
        return $prices;
    }
}