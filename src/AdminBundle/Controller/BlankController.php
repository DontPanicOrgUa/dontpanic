<?php

namespace AdminBundle\Controller;


use AdminBundle\Form\BlankFormType;
use RoomBundle\Entity\Blank;
use RoomBundle\Entity\Room;
use RoomBundle\Repository\RoomRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class BlankController
 * @package AdminBundle\Controller
 * @Security("has_role('ROLE_ADMIN')")
 */
class BlankController extends Controller
{
    /**
     * @Route("/rooms/{slug}/blanks", name="admin_blanks_list")
     * @Method("GET")
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function listAction($slug)
    {
        /** @var RoomRepository $roomRepository */
        $roomRepository = $this->getDoctrine()->getManager()->getRepository(Room::class);
        $room = $roomRepository->findBySlug($slug);
        return $this->render('AdminBundle:Blank:list.html.twig', [
            'room' => $room
        ]);
    }

    /**
     * @Route("/rooms/{slug}/blanks/add", name="admin_blanks_add")
     * @ParamConverter("room", options={"mapping": {"slug": "slug"}})
     * @param Request $request
     * @param Room $room
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request, Room $room)
    {
        $form = $this->createForm(BlankFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blank = $form->getData();
            $blank->setRoom($room);
            $em = $this->getDoctrine()->getManager();
            $em->persist($blank);
            $em->flush();
            $this->addFlash('success', 'New time is added.');
            return $this->redirectToRoute('admin_blanks_list', ['slug' => $room->getSlug()]);
        }

        return $this->render('AdminBundle:Blank:add.html.twig', [
            'room' => $room,
            'blankForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/rooms/{slug}/blanks/{id}/delete", name="admin_blanks_delete")
     * @ParamConverter("room", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("blank", options={"mapping": {"id": "id"}})
     * @param Room $room
     * @param Blank $blank
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Room $room, Blank $blank) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($blank);
        $em->flush();
        return $this->redirectToRoute('admin_blanks_list', ['slug' => $room->getSlug()]);
    }

    /**
     * @Route("/rooms/{slug}/blanks/{id}/edit", name="admin_blanks_edit")
     * @ParamConverter("room", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("blank", options={"mapping": {"id": "id"}})
     * @param Room $room
     * @param Blank $blank
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Room $room, Blank $blank, Request $request) {
        $form = $this->createForm(BlankFormType::class, $blank);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $blank = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($blank);
            $em->flush();
            $this->addFlash('success', 'Blank is edited.');
            return $this->redirectToRoute('admin_blanks_list', ['slug' => $room->getSlug()]);
        }

        return $this->render('AdminBundle:Blank:edit.html.twig', [
            'room' => $room,
            'blankForm' => $form->createView()
        ]);
    }
}