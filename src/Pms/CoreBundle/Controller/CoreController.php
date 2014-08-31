<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\Repository;
use Pms\CoreBundle\Entity\Category;
use Pms\CoreBundle\Entity\Buyer;
use Pms\CoreBundle\Entity\PurchaseOrder;
use Pms\CoreBundle\Entity\PurchaseOrderItem;
use Pms\CoreBundle\Entity\PurchaseRequisitionItem;
use Pms\CoreBundle\Entity\Vendor;
use Pms\CoreBundle\Entity\Item;
use Pms\CoreBundle\Entity\Project;
use Pms\CoreBundle\Entity\ProjectCostItem;
use Pms\CoreBundle\Entity\PurchaseRequisition;
use Pms\CoreBundle\Form\CategoryType;
use Pms\CoreBundle\Form\BuyerType;
use Pms\CoreBundle\Form\PurchaseOrderType;
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
    public function purchaseOrderAddAction(Request $request)
    {
        return $this->render('PmsCoreBundle:PurchaseOrder:add.html.twig', array(

        ));
    }

    public function purchaseOrderNewAction(Request $request)
    {
        $purchaseOrder = new PurchaseOrder();
        $form = $this->createForm(new PurchaseOrderType(), $purchaseOrder);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $status = '1';
                $dateOfDelivered = $form["dateOfDelivered"]->getData();
                $purchaseOrder->setStatus($status);
                $purchaseOrder->setDateOfDelivered(new \DateTime($dateOfDelivered));

                if (!empty($_POST['purchaseorder']['purchaseOrderItems'])) {
                    foreach ($_POST['purchaseorder']['purchaseOrderItems'] as $item) {
                        $pi = new PurchaseOrderItem();
                        $pi->setItem($em->getRepository('PmsCoreBundle:Item')->find($item['item']));
                        $pi->setQuantity($item['quantity']);
                        $pi->setComment($item['comment']);
                        $pi->setPurchaseOrder($purchaseOrder);
                        $purchaseOrder->addPurchaseOrderItem($pi);
                    }
                }

                $this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseOrder')->create($purchaseOrder);

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Po Successfully Add'
                );

                return $this->redirect($this->generateUrl('purchase_order_add'));
            }
        }

        return $this->render('PmsCoreBundle:PurchaseOrder:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function purchaseRequisitionAddAction(Request $request)
    {
        $dql = "SELECT a FROM PmsCoreBundle:PurchaseRequisition a WHERE a.status = 1 ORDER BY a.id DESC";

        list($purchaseRequisitions, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:PurchaseRequisition:add.html.twig', array(
            'purchaseRequisitions' => $purchaseRequisitions,
            'page' => $page,
        ));
    }

    public function purchaseRequisitionNewAction(Request $request)
    {
        $purchaseRequisition = new PurchaseRequisition();
        $form = $this->createForm(new PurchaseRequisitionType(), $purchaseRequisition);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                    $em = $this->getDoctrine()->getManager();
                    $status = '1';
                    $dateOfRequisition = $form["dateOfRequisition"]->getData();
                    $purchaseRequisition->setStatus($status);
                    $purchaseRequisition->setDateOfRequisition(new \DateTime($dateOfRequisition));

                    if (!empty($_POST['purchaserequisition']['purchaseRequisitionItems'])) {
                        foreach ($_POST['purchaserequisition']['purchaseRequisitionItems'] as $item) {
                            $pi = new PurchaseRequisitionItem();
                            $pi->setItem($em->getRepository('PmsCoreBundle:Item')->find($item['item']));
                            $pi->setQuantity($item['quantity']);
                            $pi->setDateOfRequired(new \DateTime($item['dateOfRequired']));
                            $pi->setComment($item['comment']);
                            $pi->setPurchaseRequisition($purchaseRequisition);
                            $purchaseRequisition->addPurchaseRequisitionItem($pi);
                        }
                    }

                    $this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseRequisition')->create($purchaseRequisition);

                    $this->get('session')->getFlashBag()->add(
                        'notice',
                        'Pr Successfully Add'
                    );

                return $this->redirect($this->generateUrl('purchase_requisition_add'));
            }
        }

        return $this->render('PmsCoreBundle:PurchaseRequisition:form.html.twig', array(
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

        return array($value, $page);
    }
}
