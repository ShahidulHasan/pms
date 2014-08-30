<?php

namespace Pms\CoreBundle\Controller;

use Doctrine\ORM\Repository;
use Pms\CoreBundle\Entity\Category;
use Pms\CoreBundle\Form\CategoryType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Category controller.
 *
 */
class CategoryController extends Controller
{
    public function categoryListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Category a WHERE a.parent = 0 AND a.status = 1 ORDER BY a.id DESC";

        list($category, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Category:list.html.twig', array(
            'categories' => $category,
            'page' => $page,
        ));
    }

    public function categoryDeletedListAction()
    {
        $dql = "SELECT a FROM PmsCoreBundle:Category a WHERE a.parent = 0 AND a.status = 0 ORDER BY a.id DESC";

        list($category, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Category:deletedList.html.twig', array(
            'categories' => $category,
            'page' => $page,
        ));
    }

    public function categoryAjaxAddAction(Request $request)
    {
        $categoryArray = $request->request->get('categoryArray');
        $categoryArray = explode(',',$categoryArray);

        $categoryName = $categoryArray[0];
        $updateId = $categoryArray[1];

        if($categoryName) {
            $category = $this->getDoctrine()->getRepository('PmsCoreBundle:Category')->find($updateId);
            $categoryNameCheck = $this->getDoctrine()->getRepository('PmsCoreBundle:Category')->findOneBy(
                array('categoryName' => $categoryName )
            );
            if($category) {
                $category->setCategoryName($categoryName);
                $this->getDoctrine()->getManager()->persist($category);
                $this->getDoctrine()->getManager()->flush();

                $return = array("responseCode" => 202);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } elseif($categoryNameCheck) {
                $return = array("responseCode" => 200);
                $return = json_encode($return);

                return new Response($return, 200, array('Content-Type' => 'application/json'));
            } else {
                $category = new Category();
                $category->setCategoryName($categoryName);
                $user = $this->get('security.context')->getToken()->getUser()->getId();
                $category->setCreatedBy($user);
                $category->setCreatedDate(new \DateTime());
                $category->setStatus(1);
                $category->setParent(0);

                $this->getDoctrine()->getRepository("PmsCoreBundle:Category")->create($category);

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

    public function categoryAddAction(Request $request)
    {
        $entity = new Category();

        $form = $this->createForm(new CategoryType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Category a WHERE a.parent = 0 AND a.status = 1 ORDER BY a.id DESC";

        list($category, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Category:add.html.twig', array(
            'categories' => $category,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function categoryDeletedAction(Request $request)
    {
        $entity = new Category();

        $form = $this->createForm(new CategoryType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Category a WHERE a.parent = 0 AND a.status = 0 ORDER BY a.id DESC";

        list($category, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Category:deleted.html.twig', array(
            'categories' => $category,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function categoryDeleteAction(Category $category)
    {
        $category->setStatus(0);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Category')->update($category);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Category Successfully Deleted'
        );

        return $this->redirect($this->generateUrl('category_add'));
    }

    public function categoryActiveAction(Category $category)
    {
        $category->setStatus(1);
        $this->getDoctrine()->getRepository('PmsCoreBundle:Category')->update($category);

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Category Successfully Restored'
        );

        return $this->redirect($this->generateUrl('category_deleted'));
    }

    public function categoryUpdateAction(Request $request, Category $entity)
    {
        $form = $this->createForm(new CategoryType(), $entity);

        $dql = "SELECT a FROM PmsCoreBundle:Category a WHERE a.parent = 0 AND a.status = 1 ORDER BY a.id DESC";

        list($category, $page) = $this->paginate($dql);

        return $this->render('PmsCoreBundle:Category:add.html.twig', array(
            'categories' => $category,
            'entity' => $entity,
            'form' => $form->createView(),
            'page' => $page,
        ));
    }

    public function categoryCheckAction(Request $request)
    {
        $categoryName = $request->request->get('categoryName');

        $category = $this->getDoctrine()->getRepository('PmsCoreBundle:Category')->findOneBy(
            array('categoryName' => $categoryName )
        );

        if ($category) {
            $return = array("responseCode" => 200, "category_name" => "Category already exist.");
            $return = json_encode($return);
            return new Response($return, 200, array('Content-Type' => 'application/json'));
        } else {
            $return = array("responseCode" => '404', "category_name" => "Category name available.");
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