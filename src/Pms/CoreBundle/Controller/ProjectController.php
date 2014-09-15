<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\ORM\Repository;
use Pms\CoreBundle\Entity\Project;
use Pms\CoreBundle\Form\ProjectType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Project controller.
 *
 */
class ProjectController extends Controller
{
    public function projectListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Project a WHERE a.status = 1  ORDER BY a.id DESC";

        list($project, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Project:list.html.twig', array(
            'project' => $project,
            'page' => $page,
        ));
    }

    public function projectDeletedListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Project a WHERE a.status = 0  ORDER BY a.id DESC";

        list($project, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Project:deletedList.html.twig', array(
            'project' => $project,
            'page' => $page,
        ));
    }

    public function projectAddAction(Request $request)
    {
        $entity = new Project();

        $form = $this->createForm(new ProjectType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Project a WHERE a.status = 1 ORDER BY a.id DESC";

        list($project, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Project:add.html.twig', array(
            'project' => $project,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function projectDeletedAction(Request $request)
    {
        $entity = new Project();

        $form = $this->createForm(new ProjectType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Project a WHERE a.status = 0 ORDER BY a.id DESC";

        list($project, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Project:deleted.html.twig', array(
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
            'Project Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('project_add'));
    }

    public function projectActiveAction(Project $entity)
    {
        $entity->setStatus(1);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Project')->update($entity);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Project Successfully Restored'
        );

        return $this->redirect($this->generateUrl('project_deleted'));
    }

    public function projectUpdateAction(Request $request, Project $entity)
    {
        $form = $this->createForm(new ProjectType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Project a WHERE a.status = 1 ORDER BY a.id DESC";

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

    public function projectAjaxAddAction(Request $request)
    {
        $projectArray = $request->request->get('projectArray');
        $projectArray = explode(',',$projectArray);

        $projectName = $projectArray[0];
        $updateId = $projectArray[1];
        $projectHead = $projectArray[2];
        $address = $projectArray[3];

        if($projectName) {
            $project = $this->getDoctrine()->getRepository('PmsCoreBundle:Project')->find($updateId);
            $projectNameCheck = $this->getDoctrine()->getRepository('PmsCoreBundle:Project')->findOneBy(
                array('projectName' => $projectName )
            );
            if($project) {
                $project->setProjectName($projectName);
                $project->setProjectHead($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($projectHead));
                $project->setAddress($address);
                $this->getDoctrine()->getManager()->persist($project);
                $this->getDoctrine()->getManager()->flush();

                $return = array("responseCode" => 202);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } elseif($projectNameCheck) {

                $return = array("responseCode" => 200);
                $return = json_encode($return);
                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } else {
                $project = new Project();
                $project->setProjectName($projectName);
                $project->setProjectHead($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($projectHead));
                $project->setAddress($address);
                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $project->setCreatedBy($this->getDoctrine()->getRepository('UserBundle:User')->findOneById($user));
                $project->setCreatedDate(new \DateTime());
                $project->setStatus(1);

                $this->getDoctrine()->getRepository("PmsCoreBundle:Project")->create($project);

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