<?php
/**
 * Created by PhpStorm.
 * User: mykyta
 * Date: 4/29/18
 * Time: 6:58 PM
 */

declare(strict_types=1);

namespace RoomBundle\Service\Schedule\Strategy;

use RoomBundle\Service\Schedule\Interfaces\StrategyScheduleInterface;

class EscapeRoomsSchedule implements StrategyScheduleInterface
{
    public function convert(array $schedule): array
    {
        $convertedSchedule = [];
        for ($week = 1; $week <= 4; $week++) {
            foreach ($schedule[0]['games'] as $key => $game) {
                $dates = [];
                for ($day = 1; $day <= 7; $day++) {
                    $prices = [];
                    $busy = false;
                    $corrective = false;
                    foreach ($schedule[($week * $day) - 1]['games'] as $game2) {
                        if ($game2['time'] === $game['time']) {
                            $prices = $game2['prices'];
                            $busy = $game2['booked'];
                            $corrective = $game2['corrective'];
                        }
                    }
                    $dates[] = [
                        'date' => $schedule[($week * $day) - 1]['date'],
                        'prices' => $prices,
                        'corrective' => $corrective,
                        'busy' => $busy,
                    ];
                }
                $convertedSchedule[$week - 1][$key] = [
                    'time' => $game['time'],
                    'dates' => $dates
                ];
            }
        }
        return $convertedSchedule;
    }
}