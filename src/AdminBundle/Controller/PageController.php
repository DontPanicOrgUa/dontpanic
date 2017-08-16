<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\PageFormType;
use WebBundle\Entity\Page;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class CityController
 * @package AdminBundle\Controller
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class PageController extends Controller
{
    /**
     * @Route("/pages", name="admin_pages_list")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $pages = $em->getRepository('WebBundle:Page')->findAll();

        return $this->render('AdminBundle:Page:list.html.twig', [
            'pages' => $pages
        ]);
    }

    /**
     * @Route("/pages/add", name="admin_pages_add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(PageFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $page = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();
            $this->addFlash('success', 'New page is created.');
            return $this->redirectToRoute('admin_pages_list');
        }

        return $this->render('AdminBundle:Page:add.html.twig', [
            'pageForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/pages/{slug}/edit", name="admin_pages_edit")
     * @param Request $request
     * @param Page $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Page $page)
    {
        $form = $this->createForm(PageFormType::class, $page);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $page = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();
            $this->addFlash('success', 'Page is edited.');
            return $this->redirectToRoute('admin_pages_list');
        }

        return $this->render('AdminBundle:Page:edit.html.twig', [
            'pageForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/pages/{slug}/delete", name="admin_pages_delete")
     * @param Request $request
     * @param Page $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, Page $page)
    {
//        try {
//            $em = $this->getDoctrine()->getManager();
//            $em->remove($city);
//            $em->flush();
//            $this->addFlash('success', 'City is deleted.');
//        } catch (ForeignKeyConstraintViolationException $e) {
//            $this->addFlash('errors', [
//                'Can not delete ' . $city->getName($request->getLocale()) . '.',
//                'There are registered rooms in ' . $city->getName($request->getLocale()) . '.'
//            ]);
//        }
//        return $this->redirectToRoute('admin_cities_list');
    }
}
