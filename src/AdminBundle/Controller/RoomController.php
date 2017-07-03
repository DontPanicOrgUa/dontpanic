<?php

namespace AdminBundle\Controller;


use WebBundle\Entity\Room;
use AdminBundle\Form\RoomFormType;
use AdminBundle\Service\ScheduleBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class RoomController
 * @package AdminBundle\Controller
 * @Security("has_role('ROLE_ADMIN')")
 */
class RoomController extends Controller
{
    /**
     * @Route("/rooms", name="admin_rooms_list")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_USER')")
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $rooms = $em->getRepository('WebBundle:Room')->queryFindAllByUserRights($this->getUser());

        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $rooms,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('records_per_page'))
        );

        return $this->render('AdminBundle:Room:list.html.twig', [
            'rooms' => $result
        ]);
    }

    /**
     * @Route("/rooms/add", name="admin_rooms_add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(RoomFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Room $room */
            $room = $form->getData();

            if ($logoFile = $room->getLogo()) {
                $logoUploaded = $this->get('admin.file_uploader')->upload($logoFile);
                $room->setLogo($logoUploaded);
            }

            if ($bgFile = $room->getBackground()) {
                $bgUploaded = $this->get('admin.file_uploader')->upload($bgFile);
                $room->setBackground($bgUploaded);
            }

            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository('AdminBundle:User')
                ->findOneBy([]);
            $room->addRoomManager($user);

            $em->persist($room);
            $em->flush();
            $this->addFlash('success', 'New room is added.');
            return $this->redirectToRoute('admin_rooms_list');
        }

        return $this->render('AdminBundle:Room:add.html.twig', [
            'roomForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/rooms/{slug}/edit", name="admin_rooms_edit")
     * @param Request $request
     * @param Room $room
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Room $room)
    {
        $logo = $room->getLogo();
        $background = $room->getBackground();
        if (is_file($this->getParameter('uploads_rooms_path') . '/' . $logo)) {
            $room->setLogo(
                new File($this->getParameter('uploads_rooms_path') . '/' . $logo)
            );
        }
        if (is_file($this->getParameter('uploads_rooms_path') . '/' . $background)) {
            $room->setBackground(
                new File($this->getParameter('uploads_rooms_path') . '/' . $background)
            );
        }

        $form = $this->createForm(RoomFormType::class, $room);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $room = $form->getData();

            if ($logoFile = $room->getLogo()) {
                $logoUploaded = $this->get('admin.file_uploader')->upload($logoFile);
                $room->setLogo($logoUploaded);
            } else {
                $room->setLogo($logo);
            }

            if ($bgFile = $room->getBackground()) {
                $bgUploaded = $this->get('admin.file_uploader')->upload($bgFile);
                $room->setBackground($bgUploaded);
            } else {
                $room->setBackground($background);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($room);
            $em->flush();
            $this->addFlash('success', 'Room is edited.');
            return $this->redirectToRoute('admin_rooms_list');
        }

        return $this->render('AdminBundle:Room:edit.html.twig', [
            'roomForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/rooms/{slug}/delete", name="admin_rooms_delete")
     * @param Room $room
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Room $room)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($room);
        $em->flush();
        $this->addFlash('success', 'Room is deleted.');
        return $this->redirectToRoute('admin_rooms_list');
    }

    /**
     * @Route("/rooms/{slug}", name="admin_rooms_schedule")
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_USER')")
     */
    public function showScheduleAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $room = $em->getRepository('WebBundle:Room')->findBySlugWithActualGames($slug);
        $this->denyAccessUnlessGranted('view', $room);
        $scheduleBuilder = new ScheduleBuilder($room);
        $schedule = $scheduleBuilder->collectByTime();
        if (empty($schedule)) {
            $this->addFlash('errors', ['No schedule for "' . $room->getTitle() . '".']);
            return $this->redirectToRoute('admin_rooms_list');
        }
        return $this->render('AdminBundle:Room:schedule.html.twig', [
            'room' => $room,
            'schedule' => $schedule
        ]);
    }
}
