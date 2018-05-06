<?php
/**
 * Created by PhpStorm.
 * User: mykyta
 * Date: 8/6/17
 * Time: 3:38 PM
 */

namespace WebBundle\Controller;


use RoomBundle\Entity\Room;
use RoomBundle\Entity\Feedback;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class FeedbackController extends Controller
{
    /**
     * @Route("/rooms/{slug}/feedbacks", name="web_feedbacks_add")
     * @Method("POST")
     * @param Request $request
     * @param $room
     * @return JsonResponse
     */
    public function addAction(Request $request, Room $room)
    {
        $feedbackData = $request->request->get('data');

        $em = $this->getDoctrine()->getManager();

        $feedback = new Feedback();
        $feedback->setRoom($room);
        $feedback->setName($feedbackData['name']);
        $feedback->setEmail($feedbackData['email']);
        $feedback->setPhone($feedbackData['phone']);
        $feedback->setComment($feedbackData['comment']);
        $feedback->setTime($feedbackData['time']);
        $feedback->setAtmosphere($feedbackData['atmosphere']);
        $feedback->setStory($feedbackData['story']);
        $feedback->setService($feedbackData['service']);

        $em->persist($feedback);
        $em->flush();

        $this->get('mail_sender')->sendFeedback($feedback, $room);

        return new JsonResponse([
            'success' => true,
            'data' => $feedbackData
        ], 201);
    }
}