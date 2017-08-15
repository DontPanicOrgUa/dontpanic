<?php

namespace AdminBundle\Controller;

use WebBundle\Entity\Share;
use AdminBundle\Form\ShareFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class CityController
 * @package AdminBundle\Controller
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class ShareController extends Controller
{
    /**
     * @Route("/shares", name="admin_shares_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cities = $em->getRepository('WebBundle:Share')->findAll();

        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $cities,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', $this->getParameter('knp_paginator.page_range'))
        );

        return $this->render('AdminBundle:Share:list.html.twig', [
            'shares' => $result
        ]);
    }

    /**
     * @Route("/shares/add", name="admin_shares_add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(ShareFormType::class);

        $form->handleRequest($request);

        $imageUploader = $this->get('image_uploader');
        $uploadsSharesPath = $this->getParameter('uploads_shares_path');

        if ($form->isSubmitted() && $form->isValid()) {
            $share = $form->getData();

            /** @var Share $share */
            if ($file = $share->getImgRu()) {
                $uploadedRu = $imageUploader->upload($file, $uploadsSharesPath, 8);
                $share->setImgRu($uploadedRu);
            }
            if ($file = $share->getImgEn()) {
                $uploadedEn = $imageUploader->upload($file, $uploadsSharesPath, 8);
                $share->setImgEn($uploadedEn);
            }
            if ($file = $share->getImgDe()) {
                $uploadedDe = $imageUploader->upload($file, $uploadsSharesPath, 8);
                $share->setImgDe($uploadedDe);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($share);
            $em->flush();
            $this->addFlash('success', 'New share is added.');
            return $this->redirectToRoute('admin_shares_list');
        }

        return $this->render('AdminBundle:Share:add.html.twig', [
            'shareForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/shares/{id}/edit", name="admin_shares_edit")
     * @param Request $request
     * @param Share $share
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Share $share)
    {
        $imgRu = $share->getImgRu();
        $imgEn = $share->getImgEn();
        $imgDe = $share->getImgDe();

        $imageUploader = $this->get('image_uploader');
        $uploadsSharesPath = $this->getParameter('uploads_shares_path');

        if (is_file($uploadsSharesPath . '/' . $imgRu)) {
            $share->setImgRu(new File($uploadsSharesPath . '/' . $imgRu));
        }
        if (is_file($uploadsSharesPath . '/' . $imgEn)) {
            $share->setImgEn(new File($uploadsSharesPath . '/' . $imgEn));
        }
        if (is_file($uploadsSharesPath . '/' . $imgDe)) {
            $share->setImgDe(new File($uploadsSharesPath . '/' . $imgDe));
        }

        $form = $this->createForm(ShareFormType::class, $share);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $share = $form->getData();
            if ($imgRuFile = $share->getImgRu()) {
                $imgRuUploaded = $imageUploader->upload($imgRuFile, $uploadsSharesPath, 8);
                $share->setImgRu($imgRuUploaded);
            } else {
                $share->setImgRu($imgRu);
            }
            if ($imgEnFile = $share->getImgEn()) {
                $imgEnUploaded = $imageUploader->upload($imgEnFile, $uploadsSharesPath, 8);
                $share->setImgEn($imgEnUploaded);
            } else {
                $share->setImgEn($imgEn);
            }
            if ($imgDeFile = $share->getImgDe()) {
                $imgDeUploaded = $imageUploader->upload($imgDeFile, $uploadsSharesPath, 8);
                $share->setImgDe($imgDeUploaded);
            } else {
                $share->setImgDe($imgDe);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($share);
            $em->flush();
            $this->addFlash('success', 'Share is edited.');
            return $this->redirectToRoute('admin_shares_list');
        }

        return $this->render('AdminBundle:Share:edit.html.twig', [
            'shareForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/shares/{id}/delete", name="admin_shares_delete")
     * @param Request $request
     * @param Share $share
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, Share $share)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($share);
        $em->flush();
        $this->addFlash('success', 'Share is deleted.');
        return $this->redirectToRoute('admin_shares_list');
    }
}
