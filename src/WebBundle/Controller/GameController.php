<?php

namespace WebBundle\Controller;


use DateTime;
use DateTimeZone;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use WebBundle\Entity\Bill;
use WebBundle\Entity\Room;
use WebBundle\Entity\Game;
use WebBundle\Entity\Price;
use WebBundle\Entity\Reward;
use WebBundle\Entity\Customer;
use WebBundle\Entity\Discount;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\DBAL\Exception\NonUniqueFieldNameException;
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
        $price = $this->getPrice($bookingData['priceId']);
        $bookingData['price'] = $price->getPrice(); // escaping injected price on frontend
        $discountedPrice = $this->getDiscountedPrice($bookingData);
        $bookingData['price'] = $discountedPrice['price'];
        $customer = $this->findOrCreateCustomer($bookingData);
        $discount = $this->createDiscount($customer);
        $game = $this->createGame($bookingData, $room, $customer);
        $reward = $this->createReward($discountedPrice['discount'], $price, $room, $game);
        $bookingData['currency'] = $room->getCurrency()->getCurrency();
        $bookingData['language'] = $request->getLocale();
        $bookingData['description'] = $room->getTitleEn() . ' ' . $bookingData['dateTime'];
        $bookingData['liqPay'] = $this->get('payment')->getBill($bookingData);
        $bill = $this->createBill($bookingData, $game);

        $em = $this->getDoctrine()->getManager();
        $em->persist($customer);
        $em->persist($game);
        $em->persist($discount);
        $em->persist($bill);
        if ($reward) {
            $em->persist($reward);
        }
        try {
            $em->flush();
        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse([
                'status' => 'fail',
                'message' => $this->get('translator')->trans('booking.busy'),
                'exception' => $e->getMessage()
            ], 409);
        } catch (\Exception $e) {
            $this->get('debug.logger')->error('booking failed on SQL', [$e->getMessage()]);
            mail('mp091689@gmail.com', 'ESCAPEROOMS FAIL', $e->getMessage());
            return new JsonResponse([
                'status' => 'fail',
                'message' => $this->get('translator')->trans('fatal'),
                'exception' => $e->getMessage()
            ], 500);
        }

        try {
            $this->get('mail_sender')->sendBooked($bookingData, $room, $discount);
        } catch (\Exception $e) {
            $this->get('debug.logger')->error('mail_sender error', [$e->getMessage()]);
        }

        try {
            if ($reward) {
                $this->get('mail_sender')->sendReward($reward, $room);
            }
        } catch (\Exception $e) {
            $this->get('debug.logger')->error('mail_sender error', [$e->getMessage()]);
        }

        try {
            $this->get('turbosms_sender')->send($bookingData, $room);
        } catch (\Exception $e) {
            $this->get('debug.logger')->error('turbosms_sender error', [$e->getMessage()]);
        }

        return new JsonResponse([
            'status' => 'success',
            'message' => $this->get('translator')->trans('booking.success'),
            'data' => $bookingData
        ], 201);
    }

    private function createGame($bookingData, Room $room, Customer $customer): Game
    {
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
        return $game;
    }

    private function createDiscount(Customer $customer): Discount
    {
        $discount = new Discount();
        $discount->setCustomer($customer);
        $discount->setDiscount($this->getParameter('discount')['discount']);
        $discount->setCode(substr(md5(uniqid(rand(), true)), 0, 8));
        return $discount;
    }

    private function findOrCreateCustomer($bookingData): Customer
    {
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
            $customer->setPercentage($this->getParameter('discount')['reward']);
        }
        return $customer;
    }

    private function getDiscountedPrice($bookingData)
    {
        $em = $this->getDoctrine()->getManager();
        $discount = $em
            ->getRepository('WebBundle:Discount')
            ->findOneByCodeObject($bookingData['discount']);
        if ($discount) {
            return [
                'discount' => $discount,
                'price' => round(((100 - $discount->getDiscount()) / 100) * $bookingData['price'], 2)
            ];
        }
        return [
            'discount' => $discount,
            'price' => $bookingData['price']
        ];
    }

    private function getPrice($priceId): Price
    {
        $em = $this->getDoctrine()->getManager();
        return $em
            ->getRepository('WebBundle:Price')
            ->find($priceId);
    }

    private function createBill($bookingData, Game $game): Bill
    {
        $bill = new Bill();
        $bill->setGame($game);
        $bill->setOrderId($bookingData['liqPay']['options']['order_id']);
        $bill->setData(json_encode($bookingData['liqPay']['options']));
        return $bill;
    }

    private function createReward($discount, Price $price, Room $room, Game $game)
    {
        if (!$discount) {
            return null;
        }
        /** @var Discount $discount */
        $reward = new Reward();
        $reward->setAmount($discount->getCustomer()->getPercentage() / 100 * $price->getPrice());
        $reward->setCurrency($room->getCurrency());
        $reward->setDiscount($discount);
        $reward->setGame($game);
        $reward->setCustomer($discount->getCustomer());
        return $reward;
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