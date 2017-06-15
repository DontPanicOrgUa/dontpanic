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
        $room = $em->getRepository('WebBundle:Room')->findBySlug($slug);
        $scheduleBuilder = new ScheduleBuilder($room);
        if ($room) {
            return $this->render('WebBundle:Room:schedule.html.twig', [
                'cities' => $this->getCities(),
                'room' => $room,
                'schedule' => $scheduleBuilder->collectByTime()
            ]);
        }
        throw $this->createNotFoundException('Room ' . $slug . ' not found');
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