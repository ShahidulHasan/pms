<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\ORM\Repository;
use Pms\CoreBundle\Entity\PurchaseRequisitionItem;
use Pms\CoreBundle\Entity\Receive;
use Pms\CoreBundle\Entity\ReceivedItem;
use Pms\CoreBundle\Form\ReceiveType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Receive controller.
 *
 */
class ReceiveController extends Controller
{
    private function  receiveNewAdd()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('PmsCoreBundle:PurchaseRequisitionItem')
            ->createQueryBuilder('pri')
            ->where('pri.status = 1')
            ->andWhere('pri.poApprovalStatus = 3');
        $pri = $query->getQuery()->getResult();

        return $this->render('PmsCoreBundle:Receive:new.html.twig', array(
            'pri' => $pri,
        ));
    }

    public function receiveDetailsAction($id)
    {
        $dql = "SELECT a FROM PmsCoreBundle:Receive a WHERE a.id = '$id'";

        $receives = $this->details($dql);

        return $this->render('PmsCoreBundle:Receive:details.html.twig', array(
            'receives' => $receives,
        ));
    }

    public function receiveDeliveryAction(Receive $receive)
    {
        $receive->setStatus('0');
        $user = $this->get('security.context')->getToken()->getUser()->getId();
        $receive->setClosedBy($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($user));
        $receive->setClosedDate(new \DateTime());
        $this->getDoctrine()->getRepository('PmsCoreBundle:Receive')->update($receive);
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Delivered Successfully'
        );

        return $this->redirect($this->generateUrl('receive_add'));
    }

    public function receiveNewAction(Request $request)
    {
        $items = $request->request->get('items');

        if ($request->getMethod() == 'POST' && !empty($items)) {

            $em = $this->getDoctrine()->getManager();
            $pir = new Receive();
            $it1 =array();
            foreach ($items as $item) {
                $pi = new ReceivedItem();
                $it = $em->getRepository('PmsCoreBundle:PurchaseRequisitionItem')->find($item);
                $ite = $em->getRepository('PmsCoreBundle:Item')->find($it->getItem());
                $itp = $em->getRepository('PmsCoreBundle:PurchaseRequisition')->find($it->getPurchaseRequisition());
                $itpr = $em->getRepository('PmsCoreBundle:Project')->find($itp->getProject());
                $pi->setPurchaseRequisitionItem($it);
                $pi->setItem($it->getItem());
                $pi->setQuantity($it->getQuantity());
                $pir->addReceiveItem($pi);
                $it1[]       = $ite->getItemName();
                $pr1[]       = $itpr->getProjectName();
            }

            $form = $this->createForm(new ReceiveType(), $pir);

            return $this->render('PmsCoreBundle:Receive:form.html.twig', array(
                'orderItems' => $items,
                'item' => $it1,
                'project' => $pr1,
                'form' => $form->createView(),
            ));
        }

        return $this->receiveNewAdd();
    }

    public function receiveAddAction(Request $request)
    {
        $dqlAll = "SELECT a FROM PmsCoreBundle:Receive a WHERE a.status = 1 ORDER BY a.id DESC";

        $allReceives = $this->paginate($dqlAll);
        $allPage = $allReceives->getCurrentPageNumber();

        $dqlClose = "SELECT a FROM PmsCoreBundle:Receive a WHERE a.status = 0 ORDER BY a.id DESC";

        $closeReceives = $this->paginate($dqlClose);
        $closePage = $closeReceives->getCurrentPageNumber();

        return $this->render('PmsCoreBundle:Receive:add.html.twig', array(
            'allReceives' => $allReceives,
            'closeReceives' => $closeReceives,
            'allPage' => $allPage,
            'closePage' => $closePage,
        ));
    }

    public function receiveSaveAction(Request $request)
    {
        $receivedItem = new Receive();

        $form = $this->createForm(new ReceiveType(), $receivedItem);
        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $receivedItem->setReceivedBy($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($user));
                $receivedItem->setReceivedDate(new \DateTime());
                $receivedItem->setStatus('1');

                /** @var ReceivedItem $item */
                foreach ($receivedItem->getReceiveItems() as $item) {
                    $purchaseRequisitionItem = $item->getPurchaseRequisitionItem();

                    $quantityOld = $purchaseRequisitionItem->getReceivedQuantity();
                    $purchaseRequisitionItem->setReceivedQuantity($quantityOld + $item->getQuantity());

                    if($purchaseRequisitionItem->getReceivedQuantity() >= $purchaseRequisitionItem->getQuantity()) {
                        $purchaseRequisitionItem->setStatus('0');
                    }

                    $item->setReceive($receivedItem);

                    $purchaseRequisitionItem->getPurchaseRequisition()->setTotalReceiveItemQuantity($purchaseRequisitionItem->getPurchaseOrderQuantity() + $purchaseRequisitionItem->getPurchaseRequisition()->getTotalReceiveItemQuantity());
                }

                $this->getDoctrine()->getRepository('PmsCoreBundle:Receive')->create($receivedItem);

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Received Successfully'
                );

                return $this->redirect($this->generateUrl('receive_add'));
            }
        }

        $dql = "SELECT a FROM PmsCoreBundle:Receive a ORDER BY a.id DESC";

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

        return $value;
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
}
