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

                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $entity->setCreatedBy($user);
                $entity->setCreatedDate(new \DateTime(date('Y-m-d')));
                $entity->setStatus(1);

                $this->getDoctrine()->getRepository("PmsCoreBundle:Item")->create($entity);
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Item Successfully Add'
                );

                return $this->redirect($this->generateUrl('item_add'));
            }
        }

//        $item = $this->getDoctrine()->getRepository("PmsCoreBundle:Item")->getAll();

        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT a FROM PmsCoreBundle:Item a ";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $item = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1) /*page number*/,
            10/*limit per page*/
        );

        return $this->render('PmsCoreBundle:Item:add.html.twig', array(
            'item' => $item,
            'entity' => $entity,
            'form' => $form->createView(),
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

//        $item = $this->getDoctrine()->getRepository("PmsCoreBundle:Item")->getAll();

        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT a FROM PmsCoreBundle:Item a ";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $item = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1) /*page number*/,
            10/*limit per page*/
        );

        return $this->render('PmsCoreBundle:Item:add.html.twig', array(
            'item' => $item,
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    public function itemCheckAction(Request $request)
    {
        $user = $this->get('fos_user.user_manager')->findUserByUsername(trim($request->request->get('itemName')));

        if ($user) {
            $return = array("responseCode" => 200, "user_name" => "User name already exist.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        } else {
            $return = array("responseCode" => '404', "user_name" => "User name available.");
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

                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $entity->setCreatedBy($user);
                $entity->setCreatedDate(new \DateTime(date('Y-m-d')));
                $entity->setStatus(1);

                $this->getDoctrine()->getRepository("PmsCoreBundle:Project")->create($entity);
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Item Successfully Add'
                );

                return $this->redirect($this->generateUrl('project_add'));
            }
        }

//        $project = $this->getDoctrine()->getRepository("PmsCoreBundle:Project")->getAll();

        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT a FROM PmsCoreBundle:Project a ";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $project = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1) /*page number*/,
            10/*limit per page*/
        );

        return $this->render('PmsCoreBundle:Project:add.html.twig', array(
            'project' => $project,
            'entity' => $entity,
            'form' => $form->createView(),
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

//        $project = $this->getDoctrine()->getRepository("PmsCoreBundle:Project")->getAll();

        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT a FROM PmsCoreBundle:Project a ";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $project = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1) /*page number*/,
            10/*limit per page*/
        );

        return $this->render('PmsCoreBundle:Project:add.html.twig', array(
            'project' => $project,
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    public function projectCostAddAction(Request $request)
    {
        $entity = new ProjectCost();

        $form = $this->createForm(new ProjectCostType(), $entity);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $entity->setCreatedBy($user);
                $entity->setCreatedDate(new \DateTime(date('Y-m-d')));
                $entity->setStatus(0);

                $this->getDoctrine()->getRepository("PmsCoreBundle:ProjectCost")->create($entity);
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Item Successfully Add'
                );

                return $this->redirect($this->generateUrl('cost_add'));
            }
        }

//        $projectcost = $this->getDoctrine()->getRepository("PmsCoreBundle:ProjectCost")->getAll();

        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT a FROM PmsCoreBundle:ProjectCost a ";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $projectcost = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1) /*page number*/,
            10/*limit per page*/
        );

        return $this->render('PmsCoreBundle:ProjectCost:add.html.twig', array(
            'projectcost' => $projectcost,
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    public function projectCostApprovedAction(ProjectCost $entity)
    {
        $entity->setStatus(1);

        $user = $this->get('security.context')->getToken()->getUser()->getId();
        $entity->setApprovedBy($user);
        $entity->setApprovedDate(new \DateTime(date('Y-m-d')));

        $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCost')->update($entity);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Item Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('cost_add'));
    }

    public function projectCostUpdateAction(Request $request, ProjectCost $entity)
    {
        $form = $this->createForm(new ProjectCostType(), $entity);

        if ($request->getMethod() == 'POST') {

            $form->submit($request);

            if ($form->isValid()) {

                $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCost')->update($entity);
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Item Successfully Updated'
                );

                return $this->redirect($this->generateUrl('cost_add'));
            }
        }

//        $projectcost = $this->getDoctrine()->getRepository("PmsCoreBundle:ProjectCost")->getAll();

        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT a FROM PmsCoreBundle:ProjectCost a ";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $projectcost = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1) /*page number*/,
            10/*limit per page*/
        );

        return $this->render('PmsCoreBundle:ProjectCost:add.html.twig', array(
            'projectcost' => $projectcost,
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }
}
