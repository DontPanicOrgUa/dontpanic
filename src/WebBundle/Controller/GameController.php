<?php

namespace WebBundle\Controller;


use DateTime;
use DateTimeZone;
use WebBundle\Entity\Bill;
use WebBundle\Entity\Room;
use WebBundle\Entity\Game;
use WebBundle\Entity\Price;
use WebBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class GameController extends Controller
{
    /**
     * @Route("/rooms/{slug}/games", name="web_games_add")
     * @Method("POST")
     * @param Request $request
     * @param $room
     * @return JsonResponse
     */
    public function addAction(Request $request, Room $room)
    {
        $bookingData = $request->request->get('bookingData');

        $em = $this->getDoctrine()->getManager();

        /** @var Price $price */
        $price = $em->getRepository('WebBundle:Price')->find($bookingData['priceId']);

        // if customer tried to change price on frontend, we will pass real price from database
        $bookingData['price'] = $price->getPrice();
        $bookingData['players'] = $price->getPlayers();

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
                new DateTimeZone($room->getCity()->getTimezone())
            )
        );

        $bookingData['currency'] = $room->getCurrency()->getCurrency();
        $bookingData['language'] = $request->getLocale();
        $bookingData['description'] = $room->getTitleEn() . ' ' . $bookingData['dateTime'];
        $bookingData['liqPay'] = $this->get('payment')->getBill($bookingData);

        $bill = new Bill();
        $bill->setGame($game);
        $bill->setOrderId($bookingData['liqPay']['options']['order_id']);
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

    /**
     * @Route("/rooms/{slug}/results", name="web_games_view")
     * @Method("GET")
     * @param Request $request
     * @param $room
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Request $request, Room $room)
    {
        $year = $request->query->get('year') ?: date('Y');
        $month = $request->query->get('month') ?: date('m');
        $timeZone = new DateTimeZone($room->getCity()->getTimezone());
        $start = DateTime::createFromFormat('d.m.Y', '01.' . $month . '.' . $year, $timeZone);
        $end = DateTime::createFromFormat('d.m.Y', '01.' . $month . '.' . $year, $timeZone)
            ->modify('+ 1 month')
            ->modify('- 1 day');

        $em = $this->getDoctrine()->getManager();

        $cities = $em->getRepository('WebBundle:City')->findAllWithActiveRooms();
        $menu = $em->getRepository('WebBundle:Page')->findBy(['isInMenu' => true]);

        $games = $em->getRepository('WebBundle:Game')
            ->getGamesWithResultsByRoom($room->getSlug(), $start, $end);

        $pagination = $this->calendarPaginator($request, $room);

        return $this->render('WebBundle:Game:results.html.twig', [
            'cities' => $cities,
            'menu' => $menu,
            'games' => $games,
            'room' => $room,
            'pagination' => $pagination
        ]);
    }

    private function calendarPaginator(Request $request, Room $room)
    {
        $tz = new DateTimeZone($room->getCity()->getTimezone());
        $now = new DateTime('now', $tz);
        $year = $request->query->get('year') ?: $now->format('Y');
        $month = $request->query->get('month') ?: $now->format('m');
        $current = DateTime::createFromFormat('m.Y', $month . '.' . $year, $tz);
        $nextYear = DateTime::createFromFormat('m.Y', $month . '.' . $year, $tz)->modify('+ 1 year');
        $prevYear = DateTime::createFromFormat('m.Y', $month . '.' . $year, $tz)->modify('- 1 year');
        $nextMonth = DateTime::createFromFormat('m.Y', $month . '.' . $year, $tz)->modify('+ 1 month');
        $prevMonth = DateTime::createFromFormat('m.Y', $month . '.' . $year, $tz)->modify('- 1 month');
        $roomCreatedAt = $room->getCreatedAt();

        $nextYearUrl = $this->generateUrl('web_games_view', [
            'slug' => $room->getSlug(),
            'year' => $nextYear->format('Y'),
            'month' => '01'
        ]);
        $prevYearUrl = $this->generateUrl('web_games_view', [
            'slug' => $room->getSlug(),
            'year' => $prevYear->format('Y'),
            'month' => '12'
        ]);
        if ($nextYear->modify('first day of January') > $now) {
            $nextYearUrl = null;
        }
        if ($prevYear->modify('last day of December') < $roomCreatedAt) {
            $prevYearUrl = null;
        }

        $nextMonthUrl = $this->generateUrl('web_games_view', [
            'slug' => $room->getSlug(),
            'year' => $nextMonth->format('Y'),
            'month' => $nextMonth->format('m')
        ]);
        $prevMonthUrl = $this->generateUrl('web_games_view', [
            'slug' => $room->getSlug(),
            'year' => $prevMonth->format('Y'),
            'month' => $prevMonth->format('m')
        ]);
        if ($nextMonth > $now) {
            $nextMonthUrl = null;
        }
        if ($prevMonth < $roomCreatedAt) {
            $prevMonthUrl = null;
        }

        $checkDate = DateTime::createFromFormat('m.Y', '01.' . $year, $tz);
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            if ($checkDate->format('mY') !== $now->format('mY') && $checkDate < $roomCreatedAt || $checkDate > $now) {
                $months[$i] = null;
                $checkDate->modify('+ 1 month');
                continue;
            }
            $months[$i] = $this->generateUrl(
                'web_games_view',
                array_merge(
                    $request->query->all(), ['slug' => $room->getSlug(), 'month' => $i < 10 ? '0' . $i : $i]
                )
            );
            $checkDate->modify('+ 1 month');
        }

        return [
            'queryDate' => $current,
            'nextYear' => $nextYearUrl,
            'prevYear' => $prevYearUrl,
            'nextMonth' => $nextMonthUrl,
            'prevMonth' => $prevMonthUrl,
            'months' => $months
        ];
    }
}