<?php

namespace WebBundle\Controller;


use DateTime;
use DateTimeZone;
use WebBundle\Entity\Bill;
use WebBundle\Entity\Room;
use WebBundle\Entity\Game;
use WebBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class GameController extends Controller
{
    /**
     * @Route("/room/{slug}/games", name="web_games_add")
     * @Method("POST")
     * @param Request $request
     * @param $room
     * @return JsonResponse
     */
    public function addAction(Request $request, Room $room)
    {
        $bookingData = $request->request->get('bookingData');

        $em = $this->getDoctrine()->getManager();

        $customer = $em
            ->getRepository('WebBundle:Customer')
            ->findOneBy(['phone' => preg_replace("/[^0-9]/", '', $bookingData['phone'])]);
        if (!$customer) {
            $customer = new Customer();
            $customer->setName($bookingData['name']);
            $customer->setLastName($bookingData['lastName']);
            $customer->setEmail($bookingData['email']);
            $customer->setPhone($bookingData['phone']);
        }

        $game = new Game();
        $game->setRoom($room);
        $game->setCustomer($customer);
        $game->setBookedBy($bookingData['bookedBy']);
        $game->setBookingData(json_encode([
            'players' => $bookingData['players'],
            'price' => $bookingData['price'],
            'discount' => $bookingData['discount']
        ]));
        $game->setDatetime(
            new DateTime(
                $bookingData['dateTime'],
                new DateTimeZone($room->getTimezone())
            )
        );

        $bookingData['currency'] = $room->getCurrency()->getCurrency();
        $bookingData['language'] = $request->getLocale();
        $bookingData['description'] = $room->getTitleEn() . ' ' . $bookingData['dateTime'];
        $bookingData['liqPay'] = $this->get('payment')->getBill($bookingData);

        $bill = new Bill();
        $bill->setGame($game);
        $bill->setData(json_encode($bookingData['liqPay']['options']));

        $em->persist($customer);
        $em->persist($game);
        $em->persist($bill);
        $em->flush();

        $this->get('mail_sender')->sendBookedGame($bookingData, $room);
        if ($this->getParameter('sms')) {
            $this->get('turbosms_sender')->send($bookingData, $room);
        }

        return new JsonResponse([
            'success' => true,
            'data' => $bookingData
        ], 201);
    }
}