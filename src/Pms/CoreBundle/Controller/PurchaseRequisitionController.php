<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\Repository;
use Pms\CoreBundle\Entity\PurchaseRequisition;
use Pms\CoreBundle\Entity\PurchaseRequisitionItem;
use Pms\CoreBundle\Form\PurchaseRequisitionType;
use Pms\CoreBundle\Form\PurchaseRequisitionItemType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Purchase Requisition controller.
 *
 */
class PurchaseRequisitionController extends Controller
{
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
                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $purchaseRequisition->setCreatedBy($user);
                $purchaseRequisition->setCreatedDate(new \DateTime());
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
                        $pi->setStatus('1');
                        $pi->setStatusPo('0');
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

    public function purchaseRequisitionApproveByProjectHeadAction(PurchaseRequisition $purchaseRequisition)
    {
        $status = '1';
        $purchaseRequisition->setApprovedByProjectHead($status);
        $purchaseRequisition->setApprovedDateProjectHead(new \DateTime());
        $this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseRequisition')->update($purchaseRequisition);
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Purchase Requisition Successfully Approved'
        );

        return $this->redirect($this->generateUrl('purchase_requisition_add'));
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