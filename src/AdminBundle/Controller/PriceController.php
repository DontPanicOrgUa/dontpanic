<?php

namespace AdminBundle\Controller;


use WebBundle\Entity\Room;
use WebBundle\Entity\Price;
use AdminBundle\Form\PriceFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class PriceController
 * @package AdminBundle\Controller
 * @Security("has_role('ROLE_ADMIN')")
 */
class PriceController extends Controller
{
    /**
     * @Route("/rooms/{slug}/blanks/prices/add", name="admin_prices_add")
     * @ParamConverter("room", options={"mapping": {"slug": "slug"}})
     * @param Room $room
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Room $room, Request $request)
    {
        $form = $this->createForm(PriceFormType::class, null, ['blanks' => $room->getBlanks()]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $price = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($price);
            $em->flush();
            $this->addFlash('success', 'New price is added.');
            return $this->redirectToRoute('admin_blanks_list', ['slug' => $room->getSlug()]);
        }
        return $this->render('AdminBundle:Price:add.html.twig', [
            'room' => $room,
            'priceForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/rooms/{slug}/blanks/prices/{id}/delete", name="admin_prices_delete")
     * @ParamConverter("room", options={"mapping": {"slug": "slug"}})
     * @param Price $price
     * @param Room $room
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Price $price, Room $room)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($price);
        $em->flush();
        return $this->redirectToRoute('admin_blanks_list', ['slug' => $room->getSlug()]);
    }

    /**
     * @Route("/rooms/{slug}/blanks/prices/{id}/edit", name="admin_prices_edit")
     * @ParamConverter("room", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("price", options={"mapping": {"id": "id"}})
     * @param Room $room
     * @param Price $price
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Room $room, Price $price, Request $request)
    {
        $form = $this->createForm(PriceFormType::class, $price);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $price = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($price);
            $em->flush();
            $this->addFlash('success', 'Price is edited.');
            return $this->redirectToRoute('admin_blanks_list', ['slug' => $room->getSlug()]);
        }
        return $this->render('AdminBundle:Price:edit.html.twig', [
            'room' => $room,
            'priceForm' => $form->createView()
        ]);
    }
}