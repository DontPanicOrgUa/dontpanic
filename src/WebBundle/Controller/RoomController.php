<?php

namespace WebBundle\Controller;


use WebBundle\Entity\City;
use AdminBundle\Service\ScheduleBuilder;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RoomController extends Controller
{
    /**
     * @Route("/room/{slug}", name="room_schedule")
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function scheduleAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $room = $em
            ->getRepository('WebBundle:Room')
            ->findBySlugForWeb($slug);
        if (!$room) {
            throw $this->createNotFoundException('The room does not exist');
        }
        $scheduleBuilder = new ScheduleBuilder($room);
        $schedule = $scheduleBuilder->collect();
        if (empty($schedule)) {
            $this->addFlash('errors', ['No schedule for "' . $room->getTitle() . '".']);
            return $this->redirectToRoute('admin_rooms_list');
        }
        return $this->render('WebBundle:Room:schedule.html.twig', [
            'cities' => $this->getCities(),
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
        return $em->getRepository('WebBundle:City')->findAllWithActiveRooms();
    }
}