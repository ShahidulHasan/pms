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

    private function purchaseOrderNewAdd()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('PmsCoreBundle:PurchaseRequisitionItem')
            ->createQueryBuilder('pri')
            ->where('pri.quantity > pri.purchaseOrderQuantity');
        $pri = $query->getQuery()->getResult();

        return $this->render('PmsCoreBundle:PurchaseOrder:new.html.twig', array(
            'pri' => $pri,
        ));
    }

    public function purchaseOrderNewAction(Request $request)
    {
        $items = $request->request->get('items');

        if ($request->getMethod() == 'POST' && !empty($items)) {
            return $this->purchaseOrderForm($items);
        }

        return $this->purchaseOrderNewAdd();
    }

    public function purchaseOrderSaveAction(Request $request)
    {
        $purchaseOrder = new PurchaseOrder();
        $form = $this->createForm(new PurchaseOrderType(), $purchaseOrder);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $purchaseOrder->setCreatedBy($user);
                $purchaseOrder->setCreatedDate(new \DateTime());
                $purchaseOrder->setStatus('1');

                /** @var PurchaseOrderItem $item */
                foreach ($purchaseOrder->getPurchaseOrderItems() as $item) {
                    $purchaseRequisitionItem = $item->getPurchaseRequisitionItem();

                    $quantityOld = $purchaseRequisitionItem->getPurchaseOrderQuantity();
                    $purchaseRequisitionItem->setPurchaseOrderQuantity($quantityOld + $item->getQuantity());

                    $item->setPurchaseRequisitionItem($purchaseRequisitionItem);
                    $item->setPurchaseOrder($purchaseOrder);
                    $item->setStatus('1');
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

    public function purchaseOrderDetailsAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('PmsCoreBundle:PurchaseOrder')
            ->createQueryBuilder('po')
            ->select('po.orderNo')
            ->where('po.id = ?1')
            ->setParameter('1', $id);
        $po = $query->getQuery()->getResult();

        return $this->render('PmsCoreBundle:PurchaseOrder:details.html.twig', array(
            'po' => $po,
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

    /**
     * @param $items
     * @return Response
     */
    protected function purchaseOrderForm($items)
    {
        $purchaseOrder = new PurchaseOrder();
        $em = $this->getDoctrine()->getManager();
        foreach ($items as $item) {
            $pi = new PurchaseOrderItem();
            $it = $em->getRepository('PmsCoreBundle:PurchaseRequisitionItem')->find($item);
            $quantityRequisition = $it->getQuantity();
            $quantityPurchaseOrder = $it->getPurchaseOrderQuantity();
            $quantityRest = ($quantityRequisition - $quantityPurchaseOrder);
            $pi->setPurchaseRequisitionItem($it);
            $pi->setQuantity($quantityRest);
            $purchaseOrder->addPurchaseOrderItem($pi);
        }

        $form = $this->createForm(new PurchaseOrderType(), $purchaseOrder);

        return $this->render('PmsCoreBundle:PurchaseOrder:form.html.twig', array(
            'orderItems' => $items,
            'form' => $form->createView(),
        ));
    }
} 