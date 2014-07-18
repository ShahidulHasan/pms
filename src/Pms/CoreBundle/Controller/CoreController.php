<?php

namespace Pms\CoreBundle\Controller;

use Pms\CoreBundle\Entity\Project;
use Pms\CoreBundle\Entity\ProjectCost;
use Pms\CoreBundle\Form\ProjectCostType;
use Pms\CoreBundle\Form\ProjectType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Repository;
use Symfony\Component\HttpFoundation\Request;
use Pms\CoreBundle\Entity\Item;
use Pms\CoreBundle\Form\ItemType;
use Symfony\Component\HttpFoundation\Response;

class CoreController extends Controller
{
    public function itemAddAction(Request $request)
    {
        $entity = new Item();

        $form = $this->createForm(new ItemType(), $entity);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $itemName = $form->get('itemName')->getData();

                $item = $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->findOneBy(
                    array('itemName' => $itemName )
                );

                if ($item == null) {

    //                $var = $form->get('itemName')->getData();
    //                var_dump($var);die;

    //                $product = $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->findOneBy(
    //                    array('itemName' => 'shanto')
    //                );
    //                if($product == true){
    //                echo('ok');die;
    //                }
    //                echo('no');die;

                    $user = $this->get('security.context')->getToken()->getUser()->getId();
                    $entity->setCreatedBy($user);
                    $entity->setCreatedDate(new \DateTime());
                    $entity->setStatus(1);

                    $this->getDoctrine()->getRepository("PmsCoreBundle:Item")->create($entity);
                    $this->get('session')->getFlashBag()->add(
                        'notice',
                        'Item Successfully Add'
                    );
                 }

                return $this->redirect($this->generateUrl('item_add'));
            }
        }

        $dql = "SELECT a FROM PmsCoreBundle:Item a ";

        list($item, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Item:add.html.twig', array(
            'item' => $item,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function itemDeleteAction(Item $entity)
    {
        $entity->setStatus(0);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->update($entity);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Item Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('item_add'));
    }

    public function itemUpdateAction(Request $request, Item $entity)
    {
        $form = $this->createForm(new ItemType(), $entity);

        if ($request->getMethod() == 'POST') {

            $form->submit($request);

            if ($form->isValid()) {

                $this->getDoctrine()->getRepository('PmsCoreBundle:Item')->update($entity);
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Item Successfully Updated'
                );

                return $this->redirect($this->generateUrl('item_add'));
            }
        }

        $dql = "SELECT a FROM PmsCoreBundle:Item a ";

        list($item, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Item:add.html.twig', array(
            'item' => $item,
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

    public function projectAddAction(Request $request)
    {
        $entity = new Project();

        $form = $this->createForm(new ProjectType(), $entity);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $projectName = $form->get('projectName')->getData();

                $item = $this->getDoctrine()->getRepository('PmsCoreBundle:Project')->findOneBy(
                    array('projectName' => $projectName )
                );

                if ($item == null) {

                    $user = $this->get('security.context')->getToken()->getUser()->getId();
                    $entity->setCreatedBy($user);
                    $entity->setCreatedDate(new \DateTime());
                    $entity->setStatus(1);

                    $this->getDoctrine()->getRepository("PmsCoreBundle:Project")->create($entity);
                    $this->get('session')->getFlashBag()->add(
                        'notice',
                        'Item Successfully Add'
                    );
                }

                return $this->redirect($this->generateUrl('project_add'));
            }
        }

        $dql = "SELECT a FROM PmsCoreBundle:Project a ";

        list($project, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Project:add.html.twig', array(
            'project' => $project,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function projectDeleteAction(Project $entity)
    {
        $entity->setStatus(0);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Project')->update($entity);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Item Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('project_add'));
    }

    public function projectUpdateAction(Request $request, Project $entity)
    {
        $form = $this->createForm(new ProjectType(), $entity);

        if ($request->getMethod() == 'POST') {

            $form->submit($request);

            if ($form->isValid()) {

                $this->getDoctrine()->getRepository('PmsCoreBundle:Project')->update($entity);
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Item Successfully Updated'
                );

                return $this->redirect($this->generateUrl('project_add'));
            }
        }

        $dql = "SELECT a FROM PmsCoreBundle:Project a ";

        list($project, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Project:add.html.twig', array(
            'project' => $project,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function projectCheckAction(Request $request)
    {
        $projectName = $request->request->get('projectName');

        $project = $this->getDoctrine()->getRepository('PmsCoreBundle:Project')->findOneBy(
            array('projectName' => $projectName )
        );

        if ($project) {
            $return = array("responseCode" => 200, "project_name" => "Project name already exist.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        } else {
            $return = array("responseCode" => '404', "project_name" => "Project name available.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        }
    }

    public function projectCostAddAction(Request $request)
    {
        $entity = new ProjectCost();

        $form = $this->createForm(new ProjectCostType(), $entity);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $entity->setDateOfCost(new \DateTime($form->getData()->getDateOfCost()));
                $entity->setCreatedBy($user);
                $entity->setCreatedDate(new \DateTime());
                $entity->setStatus(0);

                $this->getDoctrine()->getRepository("PmsCoreBundle:ProjectCost")->create($entity);
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Item Successfully Add'
                );

                return $this->redirect($this->generateUrl('cost_add'));
            }
        }

        $dql = "SELECT a FROM PmsCoreBundle:ProjectCost a WHERE 1 = 1 ";

        if(!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $dql = $this->searchByDate($_GET['start_date'], $_GET['end_date'], $dql);
        }

        list($projectcost, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:ProjectCost:add.html.twig', array(
            'projectcost' => $projectcost,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function projectCostApprovedAction(ProjectCost $entity)
    {
        $entity->setStatus(1);

        $user = $this->get('security.context')->getToken()->getUser()->getId();
        $entity->setApprovedBy($user);
        $entity->setApprovedDate(new \DateTime());

        $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCost')->update($entity);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Item Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('cost_add'));
    }

    public function projectCostUpdateAction(Request $request, ProjectCost $entity)
    {
//        $request = $this->getRequest();
//        $id = $request->get('id');
//        $date = $entity->getLineTotal();

        $date = $entity->getDateOfCost();
        $date1 =  $date->format('Y-m-d');
        $date1 = $entity->setDateOfCost($date1);

        $form = $this->createForm(new ProjectCostType(), $entity);

        if ($request->getMethod() == 'POST') {

            $form->submit($request);

            if ($form->isValid()) {
                $entity->setDateOfCost(new \DateTime($form->getData()->getDateOfCost()));
                $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCost')->update($entity);
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Item Successfully Updated'
                );

                return $this->redirect($this->generateUrl('cost_add'));
            }
        }

        $dql = "SELECT a FROM PmsCoreBundle:ProjectCost a WHERE 1 = 1 ";

        if(!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $dql = $this->searchByDate($_GET['start_date'], $_GET['end_date'], $dql);
        }

        list($projectcost, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:ProjectCost:add.html.twig', array(
            'projectcost' => $projectcost,
            'entity' => $entity,
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

    public function searchByDate($start_date, $end_date, $dql)
    {
        $dql .= "AND a.dateOfCost >= '{$start_date}' AND a.dateOfCost <= '{$end_date}'";

        return $dql;
    }
}
