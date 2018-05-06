<?php

namespace WebBundle\Controller;


use RoomBundle\Entity\City;
use RoomBundle\Entity\Room;
use RoomBundle\Service\Schedule\ScheduleService;
use RoomBundle\Service\Schedule\Strategy\EscapeRoomsSchedule;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use WebBundle\Entity\Page;

class RoomController extends Controller
{
    /**
     * @Route("/rooms/{slug}", name="web_rooms_schedule")
     * @param Request $request
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function scheduleAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $room = $em
            ->getRepository(Room::class)
            ->findBySlugForWeb($slug);
        if (!$room) {
            throw $this->createNotFoundException('The room does not exist');
        }

        $cities = $em->getRepository(City::class)->findAllWithActiveRooms();
        $menu = $em->getRepository(Page::class)->findBy(['isInMenu' => true]);

        /** @var ScheduleService $scheduleService */
        $scheduleService = $this->get('room.schedule');
        $schedule = $scheduleService->getSchedule($room, EscapeRoomsSchedule::class);

        if (empty($schedule)) {
            $this->addFlash('errors', ['No schedule for "' . $room->getTitle($request->getLocale()) . '".']);
            return $this->redirectToRoute('admin_rooms_list');
        }
        return $this->render('WebBundle:Room:schedule.html.twig', [
            'cities' => $cities,
            'menu' => $menu,
            'room' => $room,
            'schedule' => $schedule
        ]);
    }

    /**
     * @return City[]
     */
    public function getCities()
    {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository(City::class)->findAllWithActiveRooms();
    }
}