<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\ORM\Repository;
use Pms\CoreBundle\Entity\Buyer;
use Pms\CoreBundle\Form\BuyerType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Buyer controller.
 *
 */
class BuyerController extends Controller
{
    public function buyerAddAction(Request $request)
    {
        $buyer = new Buyer();

        $form = $this->createForm(new BuyerType(), $buyer);

        $dql = "SELECT a FROM PmsCoreBundle:Buyer a WHERE a.status = 1 ORDER BY a.id DESC";

        list($buyers, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Buyer:add.html.twig', array(
            'buyers' => $buyers,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function buyerDeletedAction(Request $request)
    {
        $buyer = new Buyer();

        $form = $this->createForm(new BuyerType(), $buyer);

        $dql = "SELECT a FROM PmsCoreBundle:Buyer a WHERE a.status = 0 ORDER BY a.id DESC";

        list($buyers, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Buyer:deleted.html.twig', array(
            'buyers' => $buyers,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function buyerListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Buyer a WHERE a.status = 1 ORDER BY a.id DESC";

        list($buyers, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Buyer:list.html.twig', array(
            'buyers' => $buyers,
            'page' => $page,
        ));
    }

    public function buyerDeletedListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Buyer a WHERE a.status = 0 ORDER BY a.id DESC";

        list($buyers, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Buyer:deletedList.html.twig', array(
            'buyers' => $buyers,
            'page' => $page,
        ));
    }

    public function buyerDeleteAction(Buyer $buyer)
    {
        $buyer->setStatus(0);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Buyer')->update($buyer);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Buyer Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('buyer_add'));
    }

    public function buyerActiveAction(Buyer $buyer)
    {
        $buyer->setStatus(1);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Buyer')->update($buyer);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Buyer Successfully Restored'
        );

        return $this->redirect($this->generateUrl('buyer_deleted'));
    }

    public function buyerAjaxAddAction(Request $request)
    {
        $buyerArray = $request->request->get('buyerArray');
        $buyerArray = explode(',',$buyerArray);

        $buyerName = $buyerArray[0];
        $updateId = $buyerArray[1];

        if($buyerName) {
            $buyer = $this->getDoctrine()->getRepository('PmsCoreBundle:Buyer')->find($updateId);
            $buyerNameCheck = $this->getDoctrine()->getRepository('PmsCoreBundle:Buyer')->findOneBy(
                array('buyerName' => $buyerName )
            );
            if($buyer) {
                $buyer->setBuyerName($buyerName);
                $this->getDoctrine()->getManager()->persist($buyer);
                $this->getDoctrine()->getManager()->flush();

                $return = array("responseCode" => 202);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } elseif($buyerNameCheck) {
                $return = array("responseCode" => 200);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } else {
                $buyer = new Buyer();
                $buyer->setBuyerName($buyerName);
                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $buyer->setCreatedBy($user);
                $buyer->setCreatedDate(new \DateTime());
                $buyer->setStatus(1);

                $this->getDoctrine()->getRepository("PmsCoreBundle:Buyer")->create($buyer);

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

    public function buyerCheckAction(Request $request)
    {
        $buyerName = $request->request->get('buyerName');

        $buyer = $this->getDoctrine()->getRepository('PmsCoreBundle:Buyer')->findOneBy(
            array('buyerName' => $buyerName )
        );

        if ($buyer) {
            $return = array("responseCode" => 200, "buyer_name" => "Buyer already exist.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        } else {
            $return = array("responseCode" => '404', "buyer_name" => "Buyer name available.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        }
    }

    public function buyerUpdateAction(Request $request, Buyer $buyer)
    {
        $form = $this->createForm(new BuyerType(), $buyer);

        $dql = "SELECT a FROM PmsCoreBundle:Buyer a WHERE a.status = 1 ORDER BY a.id DESC";

        list($buyers, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Buyer:add.html.twig', array(
            'buyers' => $buyers,
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