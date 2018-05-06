<?php

namespace ApiBundle\Controller;

use RoomBundle\Entity\Room;
use RoomBundle\Repository\RoomRepository;
use RoomBundle\Service\Schedule\ScheduleService;
use RoomBundle\Service\Schedule\Strategy\EscapeRoomsSchedule;
use RoomBundle\Service\Schedule\Strategy\QRoomSchedule;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RoomController extends Controller
{
    /**
     * @Route("/rooms/{slug}", name="web_rooms_schedule")
     * @param Request $request
     * @param $slug
     * @return JsonResponse
     */
    public function indexAction(Request $request, string $slug): JsonResponse
    {
        /** @var RoomRepository $roomRepository */
        $roomRepository = $this->getDoctrine()->getManager()->getRepository(Room::class);
        $room = $roomRepository->findBySlugForWeb($slug);
        if (empty($room)) {
            throw $this->createNotFoundException('The room does not exist');
        }
        /** @var ScheduleService $scheduleService */
        $scheduleService = $this->get('room.schedule');
        $schedule = $scheduleService->getSchedule($room, QRoomSchedule::class);
        return new JsonResponse($schedule, 200);
    }
}
