<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\ORM\Repository;
use Pms\CoreBundle\Entity\Invoice;
use Pms\CoreBundle\Form\InvoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Upload controller.
 *
 */
class UploadController extends Controller
{
    public function uploadAddAction(Request $request)
    {
        $invoiceDql = "SELECT a FROM PmsCoreBundle:Invoice a WHERE a.invoiceOrCalan = 1 ORDER BY a.id DESC";

        $invoices = $this->paginate($invoiceDql);
        $invoicePage = $invoices->getCurrentPageNumber();

        $calanDql = "SELECT a FROM PmsCoreBundle:Invoice a WHERE a.invoiceOrCalan = 0 ORDER BY a.id DESC";

        $calans = $this->paginate($calanDql);
        $calanPage = $calans->getCurrentPageNumber();

        return $this->render('PmsCoreBundle:Document:add.html.twig', array(
            'invoices' => $invoices,
            'calans' => $calans,
            'invoicePage' => $invoicePage,
            'calanPage' => $calanPage,
        ));
    }

    public function uploadFileAction(Request $request)
    {
        $invoice = new Invoice();

        $form = $this->createForm(new InvoiceType(), $invoice);
        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $invoice->setUploadedBy($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($user));
                $invoice->setUploadedDate(new \DateTime());

                $this->getDoctrine()->getRepository('PmsCoreBundle:Invoice')->create($invoice);

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'File Successfully Upload'
                );

                return $this->redirect($this->generateUrl('upload_add'));
            }
        }

        return $this->render('PmsCoreBundle:Document:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function paginate($dql)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $value = $paginator->paginate(
            $query,
            $page = $this->get('request')->query->get('page', 1) /*page number*/,
            50/*limit per page*/
        );

        return $value;
    }
}
