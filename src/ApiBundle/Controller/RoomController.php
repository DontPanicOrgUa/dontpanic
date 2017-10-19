<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class RoomController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return new JsonResponse(['data'], 200);
    }
}
