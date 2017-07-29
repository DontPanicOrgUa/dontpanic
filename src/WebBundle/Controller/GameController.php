<?php
namespace WebBundle\Controller;


use DateTime;
use DateTimeZone;
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
            ->findOneBy(['phone' => $bookingData['phone']]);

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
        $game->setBookingData(serialize([
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

        $em->persist($customer);
        $em->persist($game);
        $em->flush();

        $this->get('mail_sender')->sendBookedGame($bookingData, $room);
        if ($this->getParameter('sms')) {
            $this->get('turbosms_sender')->send($bookingData, $room);
        }

        return new JsonResponse([
            'success' => true,
            'data' => $bookingData,
            'liqPayBtn' => $this->getLiqPayButton(1,1,'UAH', 'uk', 'test d')
        ], 201);
    }


    public function getLiqPayButton($orderId, $amount, $currency, $language, $description)
    {
        return $this->get('payment')->getButton([
            'order_id' => $orderId,
            'amount' => $amount,
            'currency' => $currency,
            'language' => $language,
            'description' => $description,
            'sandbox' => $this->getParameter('liqpay.sandbox') ? 1 : 0
        ]);
    }
}