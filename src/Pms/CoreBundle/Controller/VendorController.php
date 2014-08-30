<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\ORM\Repository;
use Pms\CoreBundle\Entity\Vendor;
use Pms\CoreBundle\Form\VendorType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * Vendor controller.
 *
 */
class VendorController extends Controller
{
    public function vendorAddAction(Request $request)
    {
        $vendor = new Vendor();

        $form = $this->createForm(new VendorType(), $vendor);

        $dql = "SELECT a FROM PmsCoreBundle:Vendor a WHERE a.status = 1 ORDER BY a.id DESC";

        list($vendors, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Vendor:add.html.twig', array(
            'vendors' => $vendors,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function vendorDeletedAction(Request $request)
    {
        $vendor = new Vendor();

        $form = $this->createForm(new VendorType(), $vendor);

        $dql = "SELECT a FROM PmsCoreBundle:Vendor a WHERE a.status = 0 ORDER BY a.id DESC";

        list($vendors, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Vendor:deleted.html.twig', array(
            'vendors' => $vendors,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function vendorListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Vendor a WHERE a.status = 1 ORDER BY a.id DESC";

        list($vendors, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Vendor:list.html.twig', array(
            'vendors' => $vendors,
            'page' => $page,
        ));
    }

    public function vendorDeletedListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Vendor a WHERE a.status = 0 ORDER BY a.id DESC";

        list($vendors, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Vendor:deletedList.html.twig', array(
            'vendors' => $vendors,
            'page' => $page,
        ));
    }

    public function vendorDeleteAction(Vendor $vendor)
    {
        $vendor->setStatus(0);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Vendor')->update($vendor);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Vendor Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('vendor_add'));
    }

    public function vendorActiveAction(Vendor $vendor)
    {
        $vendor->setStatus(1);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Vendor')->update($vendor);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Vendor Successfully Restored'
        );

        return $this->redirect($this->generateUrl('vendor_deleted'));
    }

    public function vendorAjaxAddAction(Request $request)
    {
        $vendorArray = $request->request->get('vendorArray');
        $vendorArray = explode(',',$vendorArray);

        $vendorName = $vendorArray[0];
        $updateId = $vendorArray[1];

        if($vendorName) {
            $vendor = $this->getDoctrine()->getRepository('PmsCoreBundle:Vendor')->find($updateId);
            $vendorNameCheck = $this->getDoctrine()->getRepository('PmsCoreBundle:Vendor')->findOneBy(
                array('vendorName' => $vendorName )
            );
            if($vendor) {
                $vendor->setVendorName($vendorName);
                $this->getDoctrine()->getManager()->persist($vendor);
                $this->getDoctrine()->getManager()->flush();

                $return = array("responseCode" => 202);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } elseif($vendorNameCheck) {
                $return = array("responseCode" => 200);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } else {
                $vendor = new Vendor();
                $vendor->setVendorName($vendorName);
                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $vendor->setCreatedBy($user);
                $vendor->setCreatedDate(new \DateTime());
                $vendor->setStatus(1);

                $this->getDoctrine()->getRepository("PmsCoreBundle:Vendor")->create($vendor);

                $return = array("responseCode" => 404);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            }
        } else{
            $return = array("responseCode" => 204);
            $return = json_encode($return);

            return new Response($return, 200, array('Content-Type' => 'application/json'));
        }
    }

    public function vendorCheckAction(Request $request)
    {
        $vendorName = $request->request->get('vendorName');

        $vendor = $this->getDoctrine()->getRepository('PmsCoreBundle:Vendor')->findOneBy(
            array('vendorName' => $vendorName )
        );

        if ($vendor) {
            $return = array("responseCode" => 200, "vendor_name" => "Vendor already exist.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        } else {
            $return = array("responseCode" => '404', "vendor_name" => "Vendor name available.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        }
    }

    public function vendorUpdateAction(Request $request, Vendor $vendor)
    {
        $form = $this->createForm(new VendorType(), $vendor);

        $dql = "SELECT a FROM PmsCoreBundle:Vendor a WHERE a.status = 1 ORDER BY a.id DESC";

        list($vendors, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Vendor:add.html.twig', array(
            'vendors' => $vendors,
            'form' => $form->createView(),
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
            10/*limit per page*/
        );

        return array($value, $page);
    }
} 