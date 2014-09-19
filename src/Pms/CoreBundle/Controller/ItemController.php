<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\Repository;
use Pms\CoreBundle\Entity\Item;
use Pms\CoreBundle\Form\ItemType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Item controller.
 *
 */
class ItemController extends Controller
{
    public function itemListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Item a WHERE a.status = 1 ORDER BY a.id DESC";

        list($items, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Item:list.html.twig', array(
            'items' => $items,
            'page' => $page,
        ));
    }

    public function itemDeletedListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Item a WHERE a.status = 0 ORDER BY a.id DESC";

        list($items, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Item:deletedList.html.twig', array(
            'items' => $items,
            'page' => $page,
        ));
    }

    public function itemAddAction(Request $request)
    {
        $entity = new Item();

        $form = $this->createForm(new ItemType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Item a WHERE a.status = 1 ORDER BY a.id DESC";

        list($items, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Item:add.html.twig', array(
            'items' => $items,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function itemDeletedAction(Request $request)
    {
        $entity = new Item();

        $form = $this->createForm(new ItemType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Item a WHERE a.status = 0 ORDER BY a.id DESC";

        list($items, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Item:deleted.html.twig', array(
            'items' => $items,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function itemDeleteAction(Item $item)
    {
        $item->setStatus(0);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->update($item);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Item Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('item_add'));
    }

    public function itemActiveAction(Item $item)
    {
        $item->setStatus(1);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->update($item);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Item Successfully Restored'
        );

        return $this->redirect($this->generateUrl('item_deleted'));
    }

    public function itemUpdateAction(Request $request, Item $entity)
    {
        $form = $this->createForm(new ItemType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Item a WHERE a.status = 1 ORDER BY a.id DESC";

        list($items, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Item:add.html.twig', array(
            'items' => $items,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function itemCheckAction(Request $request)
    {
        $itemName = $request->request->get('itemName');

        $item = $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->findOneBy(
            array('itemName' => $itemName )
        );

        if ($item) {
            $return = array("responseCode" => 200, "item_name" => "Item name already exist.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        } else {
            $return = array("responseCode" => '404', "item_name" => "Item name available.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        }
    }

    public function itemAjaxAddAction(Request $request)
    {
        $itemArray = $request->request->get('itemArray');
        $itemArray = explode(',',$itemArray);

        $itemName = $itemArray[0];
        $updateId = $itemArray[1];
        $itemUnit = $itemArray[2];
        $category = $itemArray[3];

        if($itemName) {
            $item = $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->find($updateId);
            $itemNameCheck = $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->findOneBy(
                array('itemName' => $itemName )
            );
            if($item) {
                $item->setItemName($itemName);
                $item->setItemUnit($itemUnit);
                $item->setCategory($this->getDoctrine()->getRepository('PmsCoreBundle:Category')->findOneById($category));
                $this->getDoctrine()->getManager()->persist($item);
                $this->getDoctrine()->getManager()->flush();

                $return = array("responseCode" => 202);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } elseif($itemNameCheck) {
                $return = array("responseCode" => 200);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } else {
                $item = new Item();
                $item->setItemName($itemName);
                $item->setItemUnit($itemUnit);
                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $item->setCreatedBy($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($user));
                $item->setCreatedDate(new \DateTime());
                $item->setStatus(1);
                $item->setCategory($this->getDoctrine()->getRepository('PmsCoreBundle:Category')->findOneById($category));

                $this->getDoctrine()->getRepository("PmsCoreBundle:Item")->create($item);

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