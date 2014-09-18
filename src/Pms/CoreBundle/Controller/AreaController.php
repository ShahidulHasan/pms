<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\ORM\Repository;
use Pms\CoreBundle\Entity\Area;
use Pms\CoreBundle\Form\AreaType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Area controller.
 *
 */
class AreaController extends Controller
{
    public function areaListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Area a WHERE a.status = 1  ORDER BY a.id DESC";

        list($area, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Area:list.html.twig', array(
            'area' => $area,
            'page' => $page,
        ));
    }

    public function areaDeletedListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Area a WHERE a.status = 0  ORDER BY a.id DESC";

        list($area, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Area:deletedList.html.twig', array(
            'area' => $area,
            'page' => $page,
        ));
    }

    public function areaAddAction(Request $request)
    {
        $entity = new Area();

        $form = $this->createForm(new AreaType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Area a WHERE a.status = 1 ORDER BY a.id DESC";

        list($area, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Area:add.html.twig', array(
            'area' => $area,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function areaDeletedAction(Request $request)
    {
        $entity = new Area();

        $form = $this->createForm(new AreaType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Area a WHERE a.status = 0 ORDER BY a.id DESC";

        list($area, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Area:deleted.html.twig', array(
            'area' => $area,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function areaDeleteAction(Area $entity)
    {
        $entity->setStatus(0);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Area')->update($entity);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Area Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('area_add'));
    }

    public function areaActiveAction(Area $entity)
    {
        $entity->setStatus(1);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Area')->update($entity);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Area Successfully Restored'
        );

        return $this->redirect($this->generateUrl('area_deleted'));
    }

    public function areaUpdateAction(Request $request, Area $entity)
    {
        $form = $this->createForm(new AreaType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Area a WHERE a.status = 1 ORDER BY a.id DESC";

        list($area, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Area:add.html.twig', array(
            'area' => $area,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function areaCheckAction(Request $request)
    {
        $areaName = $request->request->get('area');

        $area = $this->getDoctrine()->getRepository('PmsCoreBundle:Area')->findOneBy(
            array('areaName' => $areaName )
        );

        if ($area) {
            $return = array("responseCode" => 200, "area_name" => "Area name already exist.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        } else {
            $return = array("responseCode" => '404', "area_name" => "Area name available.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        }
    }

    public function areaAjaxAddAction(Request $request)
    {
        $areaArray = $request->request->get('areaArray');
        $areaArray = explode(',',$areaArray);

        $areaName = $areaArray[0];
        $updateId = $areaArray[1];

        if($areaName) {
            $area = $this->getDoctrine()->getRepository('PmsCoreBundle:Area')->find($updateId);
            $areaNameCheck = $this->getDoctrine()->getRepository('PmsCoreBundle:Area')->findOneBy(
                array('areaName' => $areaName )
            );
            if($area) {
                $area->setAreaName($areaName);
                $this->getDoctrine()->getManager()->persist($area);
                $this->getDoctrine()->getManager()->flush();

                $return = array("responseCode" => 202);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } elseif($areaNameCheck) {

                $return = array("responseCode" => 200);
                $return = json_encode($return);
                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } else {
                $area = new Area();
                $area->setAreaName($areaName);
                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $area->setCreatedBy($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($user));
                $area->setCreatedDate(new \DateTime());
                $area->setStatus(1);

                $this->getDoctrine()->getRepository("PmsCoreBundle:Area")->create($area);

                $return = array("responseCode" => '404');
                $return = json_encode($return);
                return new Response($return, 200, array('Content-Type' => 'application/json'));
            }
        } else {
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