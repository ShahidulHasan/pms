<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\Repository;
use Pms\CoreBundle\Entity\PurchaseRequisition;
use Pms\CoreBundle\Entity\PurchaseRequisitionItem;

use Pms\CoreBundle\Form\PurchaseRequisitionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Purchase Requisition controller.
 *
 */
class PurchaseRequisitionController extends Controller
{
    public function categoryWiseItemAction(Request $request) {
        $item = $request->request->get('item');

        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('PmsCoreBundle:Item')
            ->createQueryBuilder('i')
            ->select('c.id')
            ->where('i.id = ?1')
            ->setParameter('1', $item)
            ->join('i.category','c');
        $categoryId = $query->getQuery()->getResult();

        $query1 = $em->getRepository('PmsCoreBundle:Item')
            ->createQueryBuilder('i')
            ->select('i.id')
            ->addSelect('i.itemName')
            ->where('i.category = ?1')
            ->setParameter('1', $categoryId);
        $categories = $query1->getQuery()->getResult();

        $categoryWiseItem = array();
        foreach ($categories as $category) {
            $categoryWiseItem[] = $category;
        }

        $data = array(
            'responseCode' => 200,
            'categoryWiseItem' => $categoryWiseItem
        );

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
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

    public function purchaseRequisitionDetailsAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('PmsCoreBundle:PurchaseRequisition')
            ->createQueryBuilder('pr')
            ->select('pr.requisitionNo')
            ->addSelect('p.projectName')
            ->where('pr.id = ?1')
            ->setParameter('1', $id)
            ->join('pr.project','p');
        $pr = $query->getQuery()->getResult();

        $dql = "SELECT a FROM PmsCoreBundle:PurchaseRequisitionItem a WHERE a.purchaseRequisition = '$id'";

        $pri = $this->details($dql);

        return $this->render('PmsCoreBundle:PurchaseRequisition:details.html.twig', array(
            'pr' => $pr,
            'pri' => $pri,
            'id' => $id,
        ));
    }

    public function purchaseRequisitionPrintAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('PmsCoreBundle:PurchaseRequisition')
            ->createQueryBuilder('pr')
            ->select('pr.requisitionNo')
            ->addSelect('p.projectName')
            ->where('pr.id = ?1')
            ->setParameter('1', $id)
            ->join('pr.project','p');
        $pr = $query->getQuery()->getResult();

        $dql = "SELECT a FROM PmsCoreBundle:PurchaseRequisitionItem a WHERE a.purchaseRequisition = '$id'";

        $pri = $this->details($dql);

        return $this->render('PmsCoreBundle:PurchaseRequisition:print.html.twig', array(
            'pr' => $pr,
            'pri' => $pri,
            'id' => $id,
        ));
    }

    public function purchaseRequisitionClosedAction(Request $request)
    {
        $dql = "SELECT a FROM PmsCoreBundle:PurchaseRequisition a WHERE a.approveStatus = 4 ORDER BY a.id DESC";

        list($purchaseRequisitions, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:PurchaseRequisition:closed.html.twig', array(
            'purchaseRequisitions' => $purchaseRequisitions,
            'page' => $page,
        ));
    }

    public function purchaseRequisitionOpenAction(Request $request)
    {
        $dql = "SELECT a FROM PmsCoreBundle:PurchaseRequisition a WHERE a.approveStatus = 3 ORDER BY a.id DESC";

        list($purchaseRequisitions, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:PurchaseRequisition:open.html.twig', array(
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

                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $purchaseRequisition->setCreatedBy($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($user));
                $purchaseRequisition->setCreatedDate(new \DateTime());
                $purchaseRequisition->setDateOfRequisition(new \DateTime());
                $purchaseRequisition->setStatus('1');
                $purchaseRequisition->setApproveStatus('0');

                /** @var PurchaseRequisitionItem $item */
                foreach ($purchaseRequisition->getPurchaseRequisitionItems() as $item) {

                    $item->setPurchaseRequisition($purchaseRequisition);
                    $item->setPurchaseOrderQuantity('0');
                    $item->setStatus('1');
                }

                $this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseRequisition')->create($purchaseRequisition);

                $em = $this->getDoctrine()->getManager();

                $emailQuery = $em->getRepository('UserBundle:User')
                                 ->createQueryBuilder('u')
                                 ->select('u.email')
                                 ->where('u.enabled = 1');
                $emails      = $emailQuery->getQuery()->getResult();

                foreach ($emails as $email) {
                    $emailArray[] =implode(',',$email);
                }

                $reqNo = $purchaseRequisition->getRequisitionNo();
                $emailFrom = $this->get('security.context')->getToken()->getUser()->getEmail();

                $emailSend = \Swift_Message::newInstance()
                    ->setSubject('Purchase Requisition')
                    ->setFrom($emailFrom)
                    ->setTo($emailArray)
                    ->setBody(
                        $this->renderView(
                            'PmsCoreBundle:PurchaseRequisition:email.html.twig',
                            array('reqNo' => $reqNo)
                        )
                    );

                $this->get('mailer')->send($emailSend);

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

    public function purchaseRequisitionEditAction(Request $request, PurchaseRequisition $purchaseRequisition)
    {
        $form = $this->createForm(new PurchaseRequisitionType(), $purchaseRequisition);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                if (!empty($_POST['purchaserequisition']['purchaseRequisitionItems'])) {

                    $user = $this->get('security.context')->getToken()->getUser()->getId();
                    $purchaseRequisition->setUpdatedBy($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($user));
                    $purchaseRequisition->setUpdatedDate(new \DateTime());

                    foreach ($form->getData()->getPurchaseRequisitionItems() as $item) {
                        if($item->getId() == null){
                            $item->setPurchaseRequisition($purchaseRequisition);
                            $purchaseRequisition->addPurchaseRequisitionItem($item);
                        }
                    }
                }

                $this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseRequisition')->update($purchaseRequisition);

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Purchase Requisition Successfully Update'
                );

                return $this->redirect($this->generateUrl('purchase_requisition_add'));
            }
        }

        return $this->render('PmsCoreBundle:PurchaseRequisition:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function purchaseRequisitionClaimAction(PurchaseRequisitionItem $purchaseRequisitionItem)
    {
        $user = $this->get('security.context')->getToken()->getUser()->getId();
        $purchaseRequisitionItem->setClaimedBy($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($user));
        $purchaseRequisitionItem->setClaimedDate(new \DateTime());
        $this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseRequisition')->update($purchaseRequisitionItem);
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Purchase Requisition Successfully Claimed'
        );

        return $this->redirect($this->generateUrl('purchase_requisition_add'));
    }

    public function purchaseRequisitionCloseAction(PurchaseRequisition $purchaseRequisition)
    {
        $user = $this->get('security.context')->getToken()->getUser()->getId();
        $purchaseRequisition->setClosedBy($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($user));
        $purchaseRequisition->setClosedDate(new \DateTime());
        $status = '0';
        $purchaseRequisition->setStatus($status);
        $this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseRequisition')->update($purchaseRequisition);
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Purchase Requisition Successfully Closed'
        );

        return $this->redirect($this->generateUrl('purchase_requisition_add'));
    }

    public function purchaseRequisitionApproveByProjectHeadAction(PurchaseRequisition $purchaseRequisition)
    {
        $status = '1';
        $purchaseRequisition->setApproveStatus($status);
        $user = $this->get('security.context')->getToken()->getUser()->getId();
        $purchaseRequisition->setApprovedByProjectHead($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($user));
        $purchaseRequisition->setApprovedDateProjectHead(new \DateTime());
        $this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseRequisition')->update($purchaseRequisition);
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Purchase Requisition Successfully Approved'
        );

        return $this->redirect($this->generateUrl('purchase_requisition_add'));
    }

    public function purchaseRequisitionApproveByCategoryHeadOneAction(PurchaseRequisition $purchaseRequisition)
    {
        $status = '2';
        $purchaseRequisition->setApproveStatus($status);
        $user = $this->get('security.context')->getToken()->getUser()->getId();
        $purchaseRequisition->setApprovedByCategoryHeadOne($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($user));
        $purchaseRequisition->setApprovedDateCategoryHeadOne(new \DateTime());
        $this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseRequisition')->update($purchaseRequisition);
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Purchase Requisition Successfully Approved By Category Head'
        );

        return $this->redirect($this->generateUrl('purchase_requisition_add'));
    }

    public function purchaseRequisitionApproveByCategoryHeadTwoAction(PurchaseRequisition $purchaseRequisition)
    {
        $status = '3';
        $purchaseRequisition->setApproveStatus($status);
        $user = $this->get('security.context')->getToken()->getUser()->getId();
        $purchaseRequisition->setApprovedByCategoryHeadTwo($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($user));
        $purchaseRequisition->setApprovedDateCategoryHeadTwo(new \DateTime());
        $this->getDoctrine()->getRepository('PmsCoreBundle:PurchaseRequisition')->update($purchaseRequisition);
        $this->get('session')->getFlashBag()->add(
            'notice',
            'Purchase Requisition Successfully Approved By Category Head'
        );

        return $this->redirect($this->generateUrl('purchase_requisition_add'));
    }

    public function detailsPdfAction($id)
    {
//        $file = $this->getDoctrine()
//            ->getRepository('BclOfferBundle:Offer')
//            ->find($id);
//
//        $html = $this->renderView(
//            'BclOfferBundle:Offer:view.html.twig',
//            array('file' => $file)
//        );
//
//        $dompdf = $this->get('slik_dompdf');
//        // Generate the pdf
//        $dompdf->getpdf($html);
//        // Or get the output to handle it yourself
//        $pdfoutput = $dompdf->output();
//        $filePath = "uploads/file/pdf/" . $file->getId() . ".pdf";
//        file_put_contents($filePath, $pdfoutput);
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
} 