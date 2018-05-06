<?php
/**
 * Created by PhpStorm.
 * User: mykyta
 * Date: 4/29/18
 * Time: 7:02 PM
 */

namespace RoomBundle\Service\Schedule\Interfaces;


interface StrategyScheduleInterface
{
    public function convert(array $schedule): array;
}