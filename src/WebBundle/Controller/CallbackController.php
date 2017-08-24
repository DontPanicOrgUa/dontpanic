<?php
namespace WebBundle\Controller;


use WebBundle\Entity\Callback;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class CallbackController extends Controller
{
    /**
     * @Route("/callbacks", name="web_callback_add")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $callbackData = $request->request->get('data');


        $callback = new Callback();
        $callback->setName($callbackData['name']);
        $callback->setLastName($callbackData['lastName']);
        $callback->setEmail($callbackData['email']);
        $callback->setPhone($callbackData['phone']);
        $callback->setComment($callbackData['comment']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($callback);
        $em->flush();

//        $this->get('mail_sender')->sendFeedback($feedback, $room);

        return new JsonResponse([
            'success' => true,
            'data' => $callbackData
        ], 201);
    }
}