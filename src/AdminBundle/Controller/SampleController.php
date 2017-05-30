<?php

namespace AdminBundle\Controller;


use WebBundle\Entity\Room;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SampleController extends Controller
{
    /**
     * @Route("/rooms/{id}/samples", name="admin_samples_list")
     * @param Room $room
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Room $room)
    {
        return $this->render('AdminBundle:Sample:list.html.twig', [
            'room' => $room
        ]);
    }
}