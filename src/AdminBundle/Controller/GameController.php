<?php
/**
 * Created by PhpStorm.
 * User: mykyta
 * Date: 7/3/17
 * Time: 3:12 PM
 */

namespace AdminBundle\Controller;


use WebBundle\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class GameController extends Controller
{
    public function addAction()
    {
        // TODO: implement
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