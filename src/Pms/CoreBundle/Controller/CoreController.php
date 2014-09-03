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
        $document = new Invoice();

        $form = $this->createForm(new InvoiceType(), $document);
        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $this->getDoctrine()->getRepository('PmsCoreBundle:Invoice')->create($document);

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'File Successfully Upload'
                );

                return $this->redirect($this->generateUrl('upload_add'));
            }
        }

        return $this->render('PmsCoreBundle:Document:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function receiveAddAction(Request $request)
    {
        $document = new ReceivedItem();

        $form = $this->createForm(new ReceivedItemType(), $document);
        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $this->getDoctrine()->getRepository('PmsCoreBundle:ReceivedItem')->create($document);

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Received Successfully'
                );

                return $this->redirect($this->generateUrl('receive_add'));
            }
        }

        return $this->render('PmsCoreBundle:Receive:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
