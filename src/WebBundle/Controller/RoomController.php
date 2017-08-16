<?php

namespace WebBundle\Controller;


use WebBundle\Entity\City;
use AdminBundle\Service\ScheduleBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
            ->getRepository('WebBundle:Room')
            ->findBySlugForWeb($slug);
        if (!$room) {
            throw $this->createNotFoundException('The room does not exist');
        }

        $cities = $em->getRepository('WebBundle:City')->findAllWithActiveRooms();
        $menu = $em->getRepository('WebBundle:Page')->findBy(['isInMenu' => true]);

        $scheduleBuilder = new ScheduleBuilder($room);
        $schedule = $scheduleBuilder->collect();
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
        return $em->getRepository('WebBundle:City')->findAllWithActiveRooms();
    }
}