<?php
/**
 * Created by PhpStorm.
 * User: mykyta
 * Date: 4/29/18
 * Time: 7:00 PM
 */

declare(strict_types=1);

namespace RoomBundle\Service\Schedule\Strategy;

use DateTime;
use RoomBundle\Service\Schedule\Interfaces\StrategyScheduleInterface;

class QRoomSchedule implements StrategyScheduleInterface
{
    public function convert(array $schedule): array
    {
        $convertedSchedule = [];
        for ($day = 0; $day < 14; $day++) {
            $games = [];
            foreach ($schedule[$day]['games'] as $game) {
                $cost = 0;
                foreach ($game['prices'] as $price) {
                    if ($price['price'] > $cost) {
                        $cost = $price['price'];
                    }
                }
                $games[] = [
                    'timeGame' => "{$game['time']}:00",
                    'cost' => (string)$cost,
                    'busy' => $game['booked'] ? '1' : '0',
                ];
            }
            $convertedSchedule[] = [
                'date' => $this->convertDate($schedule[$day]['date']),
                'games' => $games
            ];
        }
        return $convertedSchedule;
    }

    private function convertDate(string $date): string
    {
        return DateTime::createFromFormat('d.m.Y', $date)->format('Y-m-d');
    }
}