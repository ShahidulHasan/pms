<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\ORM\Repository;
use Pms\CoreBundle\Entity\ProjectCategory;
use Pms\CoreBundle\Form\ProjectCategoryType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Project Category controller.
 *
 */
class ProjectCategoryController extends Controller
{
    public function projectCategoryListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:ProjectCategory a WHERE a.status = 1  ORDER BY a.id DESC";

        list($projectCategory, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:ProjectCategory:list.html.twig', array(
            'projectCategory' => $projectCategory,
            'page' => $page,
        ));
    }

    public function projectCategoryDeletedListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:ProjectCategory a WHERE a.status = 0  ORDER BY a.id DESC";

        list($projectCategory, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:ProjectCategory:deletedList.html.twig', array(
            'projectCategory' => $projectCategory,
            'page' => $page,
        ));
    }

    public function projectCategoryAddAction(Request $request)
    {
        $entity = new ProjectCategory();

        $form = $this->createForm(new ProjectCategoryType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:ProjectCategory a WHERE a.status = 1 ORDER BY a.id DESC";

        list($projectCategory, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:ProjectCategory:add.html.twig', array(
            'projectCategory' => $projectCategory,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function projectCategoryDeletedAction(Request $request)
    {
        $entity = new ProjectCategory();

        $form = $this->createForm(new ProjectCategoryType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:ProjectCategory a WHERE a.status = 0 ORDER BY a.id DESC";

        list($projectCategory, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:ProjectCategory:deleted.html.twig', array(
            'projectCategory' => $projectCategory,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function projectCategoryDeleteAction(ProjectCategory $entity)
    {
        $entity->setStatus(0);
        $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCategory')->update($entity);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Project Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('project_category_add'));
    }

    public function projectCategoryActiveAction(ProjectCategory $entity)
    {
        $entity->setStatus(1);
        $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCategory')->update($entity);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Project Successfully Restored'
        );

        return $this->redirect($this->generateUrl('project_category_deleted'));
    }

    public function projectCategoryUpdateAction(Request $request, ProjectCategory $entity)
    {
        $form = $this->createForm(new ProjectCategoryType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:ProjectCategory a WHERE a.status = 1 ORDER BY a.id DESC";

        list($projectCategory, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:ProjectCategory:add.html.twig', array(
            'projectCategory' => $projectCategory,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function projectCategoryCheckAction(Request $request)
    {
        $projectCategoryName = $request->request->get('projectCategoryName');

        $projectCategory = $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCategory')->findOneBy(
            array('projectCategoryName' => $projectCategoryName )
        );

        if ($projectCategory) {
            $return = array("responseCode" => 200, "project_category_name" => "Project Category name already exist.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        } else {
            $return = array("responseCode" => '404', "project_category_name" => "Project Category name available.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        }
    }

    public function projectCategoryAjaxAddAction(Request $request)
    {
        $projectCategoryArray = $request->request->get('projectCategoryArray');
        $projectCategoryArray = explode(',',$projectCategoryArray);

        $projectCategoryName = $projectCategoryArray[0];
        $updateId = $projectCategoryArray[1];

        if($projectCategoryName) {
            $projectCategory = $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCategory')->find($updateId);
            $projectCategoryNameCheck = $this->getDoctrine()->getRepository('PmsCoreBundle:ProjectCategory')->findOneBy(
                array('projectCategoryName' => $projectCategoryName )
            );
            if($projectCategory) {
                $projectCategory->setProjectCategoryName($projectCategoryName);
                $this->getDoctrine()->getManager()->persist($projectCategory);
                $this->getDoctrine()->getManager()->flush();

                $return = array("responseCode" => 202);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } elseif($projectCategoryNameCheck) {

                $return = array("responseCode" => 200);
                $return = json_encode($return);
                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } else {
                $projectCategory = new ProjectCategory();
                $projectCategory->setProjectCategoryName($projectCategoryName);
                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $projectCategory->setCreatedBy($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($user));
                $projectCategory->setCreatedDate(new \DateTime());
                $projectCategory->setStatus(1);

                $this->getDoctrine()->getRepository("PmsCoreBundle:ProjectCategory")->create($projectCategory);

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