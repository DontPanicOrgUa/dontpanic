<?php

declare(strict_types=1);

namespace RoomBundle\Service\Schedule\Interfaces;

use RoomBundle\Entity\Room;

interface ScheduleServiceInterface
{
    public function getSchedule(Room $room, string $client): array;
}