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
     * @Route("/rooms/{slug}/games", name="admin_games_list")
     * @param $slug
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($slug, Request $request)
    {
        $search = $request->query->get('search');

        $dateStart = $request->query->get('start');
        $dateEnd = $request->query->get('end');

        $em = $this->getDoctrine()->getManager();
        $games = $em
            ->getRepository('WebBundle:Game')
            ->getAllGamesByRoom($slug, $search, $dateStart, $dateEnd);

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
     * @internal param $slug
     * @internal param Game $game
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Game $game */
        $game = $em->getRepository('WebBundle:Game')->find($id);
        if (!$game) {
            throw $this->createNotFoundException('The game does not exist');
        }
        return $this->render('AdminBundle:Game:show.html.twig', [
            'game' => $game
        ]);
    }

    /**
     * @Route("/games/{id}/delete", name="admin_games_delete")
     * @param Game $game
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Game $game)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($game);
        $em->flush();
        $this->addFlash('success', 'Game is deleted.');
        return $this->redirectToRoute('admin_games_list');
    }
}