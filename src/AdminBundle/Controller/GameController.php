<?php
/**
 * Created by PhpStorm.
 * User: mykyta
 * Date: 7/3/17
 * Time: 3:12 PM
 */

namespace AdminBundle\Controller;


use DateTime;
use DateTimeZone;
use WebBundle\Entity\Game;
use WebBundle\Entity\Room;
use WebBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class GameController extends Controller
{
    /**
     * @Route("/games", name="admin_games_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $search = $request->query->get('search');

        $dateStart = $request->query->get('start');
        $dateEnd = $request->query->get('end');

        $em = $this->getDoctrine()->getManager();
        $games = $em
            ->getRepository('WebBundle:Game')
            ->queryFindAllByUserRights($search, $dateStart, $dateEnd, $this->getUser());
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $games,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('knp_paginator.page_range'))
        );

        return $this->render('AdminBundle:Game:list.html.twig', [
            'games' => $result
        ]);
    }

    /**
     * @Route("/rooms/{slug}/games/{id}", name="admin_games_show")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param Game $game
     */
    public function showAction($id)
    {
        $bookedBy = ['customer', 'admin', 'manager', 'api'];
        $em = $this->getDoctrine()->getManager();
        /** @var Game $game */
        $game = $em->getRepository('WebBundle:Game')->findByIdWithRelatedData($id);
        if (!$game) {
            throw $this->createNotFoundException('The game does not exist');
        }
        $game->setBookingData(unserialize($game->getBookingData()));
        $game->setBookedBy($bookedBy[$game->getBookedBy()]);
        return $this->render('AdminBundle:Game:show.html.twig', [
            'game' => $game
        ]);
    }

    /**
     * @Route("/rooms/{slug}/games", name="admin_games_add")
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
        ], 201);
    }

    /**
     * @Route("/rooms/{slug}/games/{id}/delete", name="admin_games_delete")
     * @param $slug
     * @param Game $game
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($slug, Game $game)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($game);
        $em->flush();
        $this->addFlash('success', 'Game is deleted.');
        return $this->redirectToRoute('admin_rooms_schedule', ['slug' => $slug]);
    }
}