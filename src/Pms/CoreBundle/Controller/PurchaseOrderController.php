<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\Repository;
use Pms\CoreBundle\Entity\PurchaseOrder;
use Pms\CoreBundle\Entity\PurchaseOrderItem;
use Pms\CoreBundle\Entity\PurchaseRequisitionItem;
use Pms\CoreBundle\Form\PurchaseOrderType;
use Pms\CoreBundle\Form\PurchaseOrderItemType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Purchase Order controller.
 *
 */
class PurchaseOrderController extends Controller
{
    public function purchaseOrderAddAction(Request $request)
    {
        $dql = "SELECT a FROM PmsCoreBundle:PurchaseOrder a WHERE a.status = 1 ORDER BY a.id DESC";

        list($purchaseOrders, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:PurchaseOrder:add.html.twig', array(
            'purchaseOrders' => $purchaseOrders,
            'page' => $page,
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
                        $pi->setStatus('1');
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

    public function purchaseOrderTotalQuantityAction(Request $request)
    {
        $item = $request->request->get('item');

        $totalQuantityPri = $this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseRequisitionItem')->totalQuantity($item);
        $totalQuantityPoi = $this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseOrderItem')->totalQuantity($item);

        if($totalQuantityPoi[0]['totalQuantity'] == null){
            $totalQuantityPoi[0]['totalQuantity'] = 0;
        }

        $totalRequiredOfQuantity = ($totalQuantityPri[0]['totalQuantity']) - ($totalQuantityPoi[0]['totalQuantity']);

        $return = array("responseCode" => 200,"quantity"=>$totalRequiredOfQuantity);
        $return = json_encode($return);

        return new Response($return, 200, array('Content-Type' => 'application/json'));
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