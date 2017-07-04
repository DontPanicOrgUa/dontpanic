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
     * @Route("/rooms/{slug}/games", name="admin_games_add")
     * @Method("POST")
     * @param Request $request
     * @param $room
     * @return JsonResponse
     */
    public function addAction(Request $request, Room $room)
    {
        $bookingData = $request->request->get('bookingData');

        $customer = new Customer();
        $customer->setName($bookingData['name']);
        $customer->setSecondname($bookingData['secondName']);
        $customer->setEmail($bookingData['email']);
        $customer->setPhone($bookingData['phone']);

        $game = new Game();
        $game->setRoom($room);
        $game->setCustomer($customer);
        $game->setBookedBy($bookingData['bookedBy']);
        $game->setBookingData(serialize([
            'discount' => $bookingData['discount']
        ]));
        $game->setDatetime(
            new DateTime(
                $bookingData['date'] . ' ' . $bookingData['time'],
                new DateTimeZone($room->getTimezone())
            )
        );

        $em = $this->getDoctrine()->getManager();
        $em->persist($customer);
        $em->persist($game);
        $em->flush();

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