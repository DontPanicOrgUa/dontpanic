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

class DontPanicSchedule implements StrategyScheduleInterface
{

    public function convert(array $schedule): array
    {
        // TODO: Implement convert() method.
    }
}