<?php

namespace AdminBundle\Controller;


use DateTime;
use DateTimeZone;
use RoomBundle\Entity\Corrective;
use RoomBundle\Entity\Room;
use RoomBundle\Repository\CorrectiveRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class CorrectiveController
 * @package AdminBundle\Controller
 *
 * Security("has_role('ROLE_ADMIN')")
 */
class CorrectiveController extends Controller
{
    /**
     * @Route("/rooms/{slug}/correctives", name="admin_correctives_add")
     * @Method("POST")
     * @param Request $request
     * @param $room
     * @return JsonResponse
     */
    public function addAction(Request $request, Room $room)
    {
        $dateTime = new DateTime($request->request->get('dateTime'), new DateTimeZone((string)$room->getCity()->getTimezone()));
        $data = $request->request->get('data');

        $em = $this->getDoctrine()->getManager();
        /** @var CorrectiveRepository $correctiveRepository */
        $correctiveRepository = $em->getRepository(Corrective::class);
        $corrective = $correctiveRepository->getCorrectiveByRoomIdAndDateTime($room->getId(), $dateTime);

        if (!$data) {
            if ($corrective) {
                return $this->deleteAction($corrective);
            }
            return new JsonResponse(['success' => true], 204);
        }

        $statusCode = 200;

        if (!$corrective) {
            $corrective = new Corrective();
            $corrective->setRoom($room);
            $corrective->setDatetime($dateTime);
            $statusCode = 201;
        }

        $corrective->setData(serialize($data));

        $em->persist($corrective);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'data' => [
                'dateTime' => $dateTime,
                'data' => $data
            ],
        ], $statusCode);
    }

    public function deleteAction($corrective)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($corrective);
        $em->flush();
        return new JsonResponse([
            'success' => true
        ], 204);
    }
}