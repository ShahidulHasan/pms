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
        $dqlAll = "SELECT a FROM PmsCoreBundle:PurchaseOrder a WHERE a.status = 1 ORDER BY a.id DESC";

        $allPurchaseOrders = $this->details($dqlAll);
        $allPage = $allPurchaseOrders->getCurrentPageNumber();

        $dqlOpen = "SELECT a FROM PmsCoreBundle:PurchaseOrder a WHERE a.approveStatus = 3 ORDER BY a.id DESC";

        $openPurchaseOrders= $this->details($dqlOpen);
        $openPage = $openPurchaseOrders->getCurrentPageNumber();

        $dqlClose = "SELECT a FROM PmsCoreBundle:PurchaseOrder a WHERE a.status = 0 ORDER BY a.id DESC";

        $closePurchaseOrders = $this->details($dqlClose);
        $closePage = $closePurchaseOrders->getCurrentPageNumber();

        return $this->render('PmsCoreBundle:PurchaseOrder:add.html.twig', array(
            'allPurchaseOrders' => $allPurchaseOrders,
            'openPurchaseOrders' => $openPurchaseOrders,
            'closePurchaseOrders' => $closePurchaseOrders,
            'allPage' => $allPage,
            'openPage' => $openPage,
            'closePage' => $closePage,
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
                $purchaseOrder->setCreatedBy($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($user));
                $purchaseOrder->setCreatedDate(new \DateTime());
                $purchaseOrder->setStatus('1');
                $purchaseOrder->setApproveStatus('0');
                $purchaseOrder->setTotalOrderItem('0');
                $purchaseOrder->setTotalOrderItemQuantity('0');

                /** @var PurchaseOrderItem $item */
                foreach ($purchaseOrder->getPurchaseOrderItems() as $item) {
                    $purchaseRequisitionItem = $item->getPurchaseRequisitionItem();

                    $quantityOld = $purchaseRequisitionItem->getPurchaseOrderQuantity();
                    $purchaseRequisitionItem->setPurchaseOrderQuantity($quantityOld + $item->getQuantity());

                    $item->setPurchaseRequisitionItem($purchaseRequisitionItem);
                    $item->setPurchaseOrder($purchaseOrder);
                    $item->setStatus('1');

                    $purchaseOrder->setTotalOrderItem(1 + $purchaseOrder->getTotalOrderItem());
                    $purchaseOrder->setTotalOrderItemQuantity($item->getQuantity() + $purchaseOrder->getTotalOrderItemQuantity());

                    $purchaseRequisitionItem->getPurchaseRequisition()->setTotalOrderItemQuantity($purchaseRequisitionItem->getPurchaseOrderQuantity() + $purchaseRequisitionItem->getPurchaseRequisition()->getTotalOrderItemQuantity());
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
            ->addSelect('po.dateOfDelivered')
            ->addSelect('u.username')
            ->addSelect('po.dateOfDelivered')
            ->addSelect('po.createdDate')
            ->where('po.id = ?1')
            ->setParameter('1', $id)
            ->join('po.createdBy', 'u');
        $po = $query->getQuery()->getResult();

        $dql = "SELECT a FROM PmsCoreBundle:PurchaseOrderItem a WHERE a.purchaseOrder = '$id'";

        $poi = $this->details($dql);

        return $this->render('PmsCoreBundle:PurchaseOrder:details.html.twig', array(
            'po' => $po,
            'poi' => $poi,
            'id' => $id,
        ));
    }

    public function purchaseOrderPrintAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('PmsCoreBundle:PurchaseOrder')
            ->createQueryBuilder('po')
            ->select('po.orderNo')
            ->addSelect('po.dateOfDelivered')
            ->addSelect('u.username')
            ->addSelect('po.dateOfDelivered')
            ->addSelect('po.createdDate')
            ->where('po.id = ?1')
            ->setParameter('1', $id)
            ->join('po.createdBy', 'u');
        $po = $query->getQuery()->getResult();

        $dql = "SELECT a FROM PmsCoreBundle:PurchaseOrderItem a WHERE a.purchaseOrder = '$id'";

        $poi = $this->details($dql);

        return $this->render('PmsCoreBundle:PurchaseOrder:print.html.twig', array(
            'po' => $po,
            'poi' => $poi,
        ));
    }

    public function purchaseOrderApproveOneAction(PurchaseOrder $purchaseOrder)
    {
        $status = '1';
        $purchaseOrder->setApproveStatus($status);
        $user = $this->get('security.context')->getToken()->getUser()->getId();
        $purchaseOrder->setApprovedOne($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($user));
        $purchaseOrder->setApprovedOneDate(new \DateTime());
        $this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseOrder')->update($purchaseOrder);
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Purchase Order Successfully Approved'
        );

        return $this->redirect($this->generateUrl('purchase_order_add'));
    }

    public function purchaseOrderApproveTwoAction(PurchaseOrder $purchaseOrder)
    {
        $status = '2';
        $purchaseOrder->setApproveStatus($status);
        $user = $this->get('security.context')->getToken()->getUser()->getId();
        $purchaseOrder->setApprovedTwo($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($user));
        $purchaseOrder->setApprovedTwoDate(new \DateTime());
        $this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseOrder')->update($purchaseOrder);
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Purchase Order Successfully Approved'
        );

        return $this->redirect($this->generateUrl('purchase_order_add'));
    }

    public function purchaseOrderApproveThreeAction(PurchaseOrder $purchaseOrder)
    {
        $status = '3';
        $purchaseOrder->setApproveStatus($status);
        $user = $this->get('security.context')->getToken()->getUser()->getId();
        $purchaseOrder->setApprovedThree($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($user));
        $purchaseOrder->setApprovedThreeDate(new \DateTime());
        $this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseOrder')->update($purchaseOrder);
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Purchase Order Successfully Approved'
        );

        return $this->redirect($this->generateUrl('purchase_order_add'));
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

    public function details($dql)
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

    /**
     * @param $items
     * @return Response
     */
    protected function purchaseOrderForm($items)
    {
        $purchaseOrder = new PurchaseOrder();
        $em = $this->getDoctrine()->getManager();
        $it1 =array();
        $pr1 =array();
        foreach ($items as $item) {
            $pi = new PurchaseOrderItem();
            $it = $em->getRepository('PmsCoreBundle:PurchaseRequisitionItem')->find($item);
            $ite = $em->getRepository('PmsCoreBundle:Item')->find($it->getItem());
            $itp = $em->getRepository('PmsCoreBundle:PurchaseRequisition')->find($it->getPurchaseRequisition());
            $itpr = $em->getRepository('PmsCoreBundle:Project')->find($itp->getProject());
            $quantityRequisition = $it->getQuantity();
            $quantityPurchaseOrder = $it->getPurchaseOrderQuantity();
            $quantityRest = ($quantityRequisition - $quantityPurchaseOrder);
            $pi->setPurchaseRequisitionItem($it);
            $pi->setQuantity($quantityRest);
            $purchaseOrder->addPurchaseOrderItem($pi);
            $it1[]                     = $ite->getItemName();
            $pr1[]                     = $itpr->getProjectName();
        }

        $form = $this->createForm(new PurchaseOrderType(), $purchaseOrder);

        return $this->render('PmsCoreBundle:PurchaseOrder:form.html.twig', array(
            'orderItems' => $items,
            'item' => $it1,
            'project' => $pr1,
            'form' => $form->createView(),
        ));
    }
} 