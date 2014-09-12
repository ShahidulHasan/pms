<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\Repository;
use Pms\CoreBundle\Entity\Category;
use Pms\CoreBundle\Entity\Buyer;
use Pms\CoreBundle\Entity\Invoice;
use Pms\CoreBundle\Entity\PurchaseOrder;
use Pms\CoreBundle\Entity\PurchaseOrderItem;
use Pms\CoreBundle\Entity\PurchaseRequisitionItem;
use Pms\CoreBundle\Entity\ReceivedItem;
use Pms\CoreBundle\Entity\Vendor;
use Pms\CoreBundle\Entity\Item;
use Pms\CoreBundle\Entity\Project;
use Pms\CoreBundle\Entity\ProjectCostItem;
use Pms\CoreBundle\Entity\PurchaseRequisition;
use Pms\CoreBundle\Form\CategoryType;
use Pms\CoreBundle\Form\BuyerType;
use Pms\CoreBundle\Form\InvoiceType;
use Pms\CoreBundle\Form\PurchaseOrderType;
use Pms\CoreBundle\Form\ReceivedItemType;
use Pms\CoreBundle\Form\VendorType;
use Pms\CoreBundle\Form\ItemType;
use Pms\CoreBundle\Form\ProjectCostItemType;
use Pms\CoreBundle\Form\ProjectType;
use Pms\CoreBundle\Form\PurchaseRequisitionType;
use Pms\CoreBundle\Form\SearchType;
use Pms\CoreBundle\Form\SubCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CoreController extends Controller
{
    public function uploadAddAction(Request $request)
    {
        $invoice = new Invoice();

        $form = $this->createForm(new InvoiceType(), $invoice);
        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $invoice->setUploadedBy($user);
                $invoice->setUploadedDate(new \DateTime());

                $this->getDoctrine()->getRepository('PmsCoreBundle:Invoice')->create($invoice);

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'File Successfully Upload'
                );

                return $this->redirect($this->generateUrl('upload_add'));
            }
        }

        $dql = "SELECT a FROM PmsCoreBundle:Invoice a ORDER BY a.id DESC";

        list($invoices, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Document:add.html.twig', array(
            'form' => $form->createView(),
            'invoices' => $invoices,
            'page' => $page,
        ));
    }

    private function  receiveNewAdd()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('PmsCoreBundle:PurchaseRequisitionItem')
            ->createQueryBuilder('pri')
            ->where('pri.status = 1');
        $pri = $query->getQuery()->getResult();

        return $this->render('PmsCoreBundle:Receive:new.html.twig', array(
            'pri' => $pri,
        ));
    }

    public function receiveNewAction(Request $request)
    {
        $items = $request->request->get('items');

        if ($request->getMethod() == 'POST' && !empty($items)) {

            $receivedItem = new ReceivedItem();
//            $em = $this->getDoctrine()->getManager();
//            foreach ($items as $item) {
//                $pi = new PurchaseOrderItem();
//                $it = $em->getRepository('PmsCoreBundle:PurchaseRequisitionItem')->find($item);
//                $quantityRequisition = $it->getQuantity();
//                $quantityPurchaseOrder = $it->getPurchaseOrderQuantity();
//                $quantityRest = ($quantityRequisition - $quantityPurchaseOrder);
//                $pi->setPurchaseRequisitionItem($it);
//                $pi->setQuantity($quantityRest);
//                $purchaseOrder->addPurchaseOrderItem($pi);
//            }

            $form = $this->createForm(new ReceivedItemType(), $receivedItem);

            return $this->render('PmsCoreBundle:Receive:form.html.twig', array(
                'orderItems' => $items,
                'form' => $form->createView(),
            ));
        }

        return $this->receiveNewAdd();
    }

    public function receiveAddAction(Request $request)
    {
        $receivedItem = new ReceivedItem();

        $form = $this->createForm(new ReceivedItemType(), $receivedItem);
        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $receivedItem->setReceivedBy($user);
                $receivedItem->setReceivedDate(new \DateTime());

                $this->getDoctrine()->getRepository('PmsCoreBundle:ReceivedItem')->create($receivedItem);

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Received Successfully'
                );

                return $this->redirect($this->generateUrl('receive_add'));
            }
        }

        $dql = "SELECT a FROM PmsCoreBundle:ReceivedItem a ORDER BY a.id DESC";

        list($receivedItems, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Receive:add.html.twig', array(
            'form' => $form->createView(),
            'receivedItems' => $receivedItems,
            'page' => $page,
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

        return array($value, $page);
    }
}
