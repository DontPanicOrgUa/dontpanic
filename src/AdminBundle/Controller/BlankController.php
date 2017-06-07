<?php

namespace AdminBundle\Controller;


use DateTime;
use WebBundle\Entity\Room;
use WebBundle\Entity\Blank;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class BlankController extends Controller
{
    /**
     * @Route("/rooms/{slug}/blanks", name="admin_blanks_list")
     * @Method("GET")
     * @param Room $room
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Room $room)
    {
        return $this->render('AdminBundle:Blank:list.html.twig', [
            'room' => $room
        ]);
    }

    /**
     * @Route("/rooms/{slug}/blanks", name="admin_blanks_add")
     * @Method("POST")
     * @param Room $room
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction(Room $room, Request $request)
    {
        $time = DateTime::createFromFormat('H:i:s', $request->request->get('time') . ':00');
        $blank = new Blank();
        $blank->setRoom($room);
        $blank->setTime($time);
        $em = $this->getDoctrine()->getManager();
        $em->persist($blank);
        $em->flush();
        return new JsonResponse([
            'blank' => [
                'id' => $blank->getId(),
                'room_id' => $blank->getRoom()->getId(),
                'time' => $blank->getTime()->format('H:i')
            ]
        ]);
    }

    /**
     * @Route("/rooms/{slug}/blanks/{id}", name="admin_blanks_delete")
     * @ParamConverter("room", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("blank", options={"mapping": {"id": "id"}})
     * @Method("DELETE")
     * @param Blank $blank
     * @return JsonResponse
     */
    public function deleteAction(Room $room, Blank $blank) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($blank);
        $em->flush();
        return new JsonResponse([
            'message' => 'success',
            'status' => 204
        ], 204);
    }

    /**
     * @Route("/rooms/{slug}/blanks/{id}", name="admin_blanks_edit")
     * @ParamConverter("room", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("blank", options={"mapping": {"id": "id"}})
     * @Method("PUT")
     * @param Room $room
     * @param Blank $blank
     * @param Request $request
     * @return JsonResponse
     */
    public function editAction(Room $room, Blank $blank, Request $request) {
        $time = DateTime::createFromFormat('H:i:s', $request->request->get('time') . ':00');
        $em = $this->getDoctrine()->getManager();
        $blank->setTime($time);
        $em->persist($blank);
        $em->flush();
        return new JsonResponse([
            'blank' => [
                'id' => $blank->getId(),
                'room_id' => $blank->getRoom()->getId(),
                'time' => $blank->getTime()->format('H:i')
            ]
        ], 200);
    }
}